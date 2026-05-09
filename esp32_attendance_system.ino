/*
 * Smart Biometric Attendance System - ESP32 Firmware
 * Hardware: ESP32 DevKit V1, AS608 Fingerprint Sensor, 16x2 I2C LCD, 4x4 Keypad
 */

#include <WiFi.h>
#include <HTTPClient.h>
#include <Adafruit_Fingerprint.h>
#include <LiquidCrystal_I2C.h>
#include <Keypad.h>
#include <ArduinoJson.h>

// --- CONFIGURATION ---
const char* ssid = "YOUR_WIFI_SSID";
const char* password = "YOUR_WIFI_PASSWORD";
const char* serverUrl = "http://172.16.114.151:8000/api"; // Update with your Laravel API URL
const char* deviceId = "ROOM_101"; // Unique ID for this classroom device

// --- HARDWARE PINOUT ---
#define FINGERPRINT_RX 16 // Connect to AS608 TX
#define FINGERPRINT_TX 17 // Connect to AS608 RX
#define BUZZER_PIN 27

// --- KEYPAD CONFIG ---
const byte ROWS = 4;
const byte COLS = 4;
char keys[ROWS][COLS] = {
  {'1','2','3','A'},
  {'4','5','6','B'},
  {'7','8','9','C'},
  {'*','0','#','D'}
};
byte rowPins[ROWS] = {13, 12, 14, 27}; 
byte colPins[COLS] = {26, 25, 33, 32};
Keypad keypad = Keypad(makeKeymap(keys), rowPins, colPins, ROWS, COLS);

// --- LCD CONFIG ---
LiquidCrystal_I2C lcd(0x27, 16, 2);

// --- FINGERPRINT SENSOR ---
HardwareSerial mySerial(2);
Adafruit_Fingerprint finger = Adafruit_Fingerprint(&mySerial);

// --- STATE VARIABLES ---
int sessionId = 0;
String currentCourse = "None";

void setup() {
  Serial.begin(115200);
  
  // Initialize LCD
  lcd.init();
  lcd.backlight();
  lcd.print("UniAttend System");
  lcd.setCursor(0, 1);
  lcd.print("Initializing...");

  // Initialize Fingerprint
  mySerial.begin(57600, SERIAL_8N1, FINGERPRINT_RX, FINGERPRINT_TX);
  if (finger.verifyPassword()) {
    Serial.println("Found fingerprint sensor!");
  } else {
    Serial.println("Did not find fingerprint sensor :(");
    lcd.clear();
    lcd.print("Sensor Error!");
    while (1) { delay(1); }
  }

  // Connect WiFi
  WiFi.begin(ssid, password);
  while (WiFi.status() != WL_CONNECTED) {
    delay(500);
    Serial.print(".");
  }
  Serial.println("\nWiFi Connected");
  
  lcd.clear();
  lcd.print("WiFi Connected");
  delay(2000);
}

void loop() {
  if (sessionId == 0) {
    lcd.setCursor(0, 0);
    lcd.print("Enter OTP:      ");
    String otp = getOtpInput();
    if (otp.length() > 0) {
      activateSession(otp);
    }
  } else {
    lcd.setCursor(0, 0);
    lcd.print(currentCourse);
    lcd.setCursor(0, 1);
    lcd.print("Scan Fingerprint");
    
    int fid = getFingerprintID();
    if (fid != -1) {
      sendAttendance(fid);
    }
  }
  delay(50);
}

String getOtpInput() {
  String input = "";
  while (input.length() < 4) {
    char key = keypad.getKey();
    if (key) {
      input += key;
      lcd.setCursor(input.length() - 1, 1);
      lcd.print(key);
      beep(100);
    }
  }
  return input;
}

void activateSession(String otp) {
  if (WiFi.status() == WL_CONNECTED) {
    HTTPClient http;
    http.begin(String(serverUrl) + "/start-session");
    http.addHeader("Content-Type", "application/json");
    
    StaticJsonDocument<200> doc;
    doc["otp"] = otp;
    doc["device_id"] = deviceId;
    
    String requestBody;
    serializeJson(doc, requestBody);
    
    int httpResponseCode = http.POST(requestBody);
    
    if (httpResponseCode == 200) {
      String payload = http.getString();
      StaticJsonDocument<500> res;
      deserializeJson(res, payload);
      sessionId = res["session_id"];
      currentCourse = res["course"].as<String>();
      
      lcd.clear();
      lcd.print("Activated!");
      lcd.setCursor(0, 1);
      lcd.print(currentCourse);
      beep(500);
    } else {
      lcd.clear();
      lcd.print("Invalid OTP!");
      delay(2000);
    }
    http.end();
  }
}

int getFingerprintID() {
  uint8_t p = finger.getImage();
  if (p != FINGERPRINT_OK) return -1;

  p = finger.image2Tz();
  if (p != FINGERPRINT_OK) return -1;

  p = finger.fingerFastSearch();
  if (p != FINGERPRINT_OK) return -1;

  Serial.print("Found ID #"); Serial.print(finger.fingerID);
  Serial.print(" with confidence of "); Serial.println(finger.confidence);
  return finger.fingerID;
}

void sendAttendance(int fid) {
  if (WiFi.status() == WL_CONNECTED) {
    HTTPClient http;
    http.begin(String(serverUrl) + "/clock-in");
    http.addHeader("Content-Type", "application/json");
    
    StaticJsonDocument<200> doc;
    doc["fingerprint_id"] = fid;
    doc["session_id"] = sessionId;
    
    String requestBody;
    serializeJson(doc, requestBody);
    
    int httpResponseCode = http.POST(requestBody);
    
    lcd.clear();
    if (httpResponseCode == 200) {
      String payload = http.getString();
      StaticJsonDocument<500> res;
      deserializeJson(res, payload);
      lcd.print("Welcome!");
      lcd.setCursor(0, 1);
      lcd.print(res["student"].as<String>());
      beep(200);
    } else {
      lcd.print("Error!");
      lcd.setCursor(0, 1);
      lcd.print("Try Again");
      beep(1000);
    }
    delay(2000);
    http.end();
  }
}

void beep(int ms) {
  digitalWrite(BUZZER_PIN, HIGH);
  delay(ms);
  digitalWrite(BUZZER_PIN, LOW);
}

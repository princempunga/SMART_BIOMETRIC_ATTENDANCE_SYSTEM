<!DOCTYPE html>
<html>
<head>
    <title>Attendance Report - {{ $course->course_name }}</title>
    <style>
        body { font-family: 'Helvetica', sans-serif; font-size: 10px; color: #333; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #2563EB; padding-bottom: 10px; }
        .header h1 { color: #0F172A; margin: 0; font-size: 18px; }
        .header p { margin: 5px 0; color: #64748B; font-weight: bold; }
        .stats { margin-bottom: 20px; background: #F8FAFC; padding: 10px; border-radius: 5px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #E2E8F0; padding: 6px 4px; text-align: center; }
        th { background: #F1F5F9; font-weight: bold; text-transform: uppercase; font-size: 8px; }
        .student-info { text-align: left; width: 150px; }
        .present { color: #059669; font-weight: bold; }
        .absent { color: #DC2626; font-weight: bold; }
        .score { background: #F0FDF4; font-weight: bold; color: #15803D; }
        .footer { text-align: right; font-size: 8px; color: #94A3B8; margin-top: 20px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Attendance Analysis Report</h1>
        <p>{{ $course->course_name }} ({{ $course->course_code }})</p>
        <p>Lecturer: {{ $lecturer->name }} • Semester: SEM1-2026</p>
    </div>

    <div class="stats">
        <strong>Report Summary:</strong> Total Students: {{ count($reportData) }} | 
        Class Average: {{ count($reportData) > 0 ? number_format(array_sum(array_column($reportData, 'score_out_of_5')) / count($reportData), 1) : '0.0' }} / 5.0
    </div>

    <table>
        <thead>
            <tr>
                <th class="student-info">Student Name & Reg</th>
                @for($i = 1; $i <= 16; $i++)
                <th>W{{ $i }}</th>
                @endfor
                <th>Time</th>
                <th class="score">Score (/5)</th>
                <th>Rate</th>
            </tr>
        </thead>
        <tbody>
            @foreach($reportData as $data)
            <tr>
                <td class="student-info">
                    <strong>{{ $data['student']->full_name }}</strong><br>
                    <small>{{ $data['student']->reg_number }}</small>
                </td>
                @for($w = 1; $w <= 16; $w++)
                <td>
                    @if(in_array($w, $weeksWithSessions))
                        @if($data['weeks'][$w])
                            <span class="present">P</span>
                        @else
                            <span class="absent">X</span>
                        @endif
                    @else
                        -
                    @endif
                </td>
                @endfor
                <td>{{ floor($data['total_duration'] / 60) }}h</td>
                <td class="score">{{ number_format($data['score_out_of_5'], 1) }}</td>
                <td>{{ number_format($data['attendance_rate'], 0) }}%</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Generated on {{ date('d M Y H:i') }} • SmartAttend Biometric System
    </div>
</body>
</html>

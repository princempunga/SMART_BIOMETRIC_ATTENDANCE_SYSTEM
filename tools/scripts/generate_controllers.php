<?php

$controllers = ['Faculty', 'Department', 'Classroom', 'Device', 'Student'];

foreach ($controllers as $model) {
    $modelLower = strtolower($model);
    $controllerPath = "app/Http/Controllers/Admin/{$model}Controller.php";
    
    $content = "<?php\n\nnamespace App\Http\Controllers\Admin;\n\nuse App\Http\Controllers\Controller;\nuse App\Models\\{$model};\nuse Illuminate\Http\Request;\nuse App\Http\Requests\Store{$model}Request;\nuse App\Http\Requests\Update{$model}Request;\n\nclass {$model}Controller extends Controller\n{\n    public function index()\n    {\n        \${$modelLower}s = {$model}::all();\n        return view('admin.{$modelLower}s.index', compact('{$modelLower}s'));\n    }\n\n    public function create()\n    {\n        return view('admin.{$modelLower}s.create');\n    }\n\n    public function store(Store{$model}Request \$request)\n    {\n        {$model}::create(\$request->validated());\n        return redirect()->route('admin.{$modelLower}s.index')->with('success', '{$model} created successfully.');\n    }\n\n    public function edit({$model} \${$modelLower})\n    {\n        return view('admin.{$modelLower}s.edit', compact('{$modelLower}'));\n    }\n\n    public function update(Update{$model}Request \$request, {$model} \${$modelLower})\n    {\n        \${$modelLower}->update(\$request->validated());\n        return redirect()->route('admin.{$modelLower}s.index')->with('success', '{$model} updated successfully.');\n    }\n\n    public function destroy({$model} \${$modelLower})\n    {\n        \${$modelLower}->delete();\n        return redirect()->route('admin.{$modelLower}s.index')->with('success', '{$model} deleted successfully.');\n    }\n}\n";
    
    file_put_contents($controllerPath, $content);
}
echo "Controllers generated successfully.\n";

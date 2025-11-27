<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Task Assigned</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px;">
    <div style="background: linear-gradient(135deg, #6E46AE 0%, #00B6B4 100%); padding: 30px; text-align: center; border-radius: 8px 8px 0 0;">
        <h1 style="color: white; margin: 0;">New Task Assigned</h1>
    </div>
    
    <div style="background: #ffffff; padding: 30px; border: 1px solid #e5e7eb; border-top: none; border-radius: 0 0 8px 8px;">
        <p style="font-size: 16px; margin-bottom: 20px;">Hello,</p>
        
        <p style="font-size: 16px; margin-bottom: 20px;">
            A new task has been assigned to you:
        </p>
        
        <div style="background: #f9fafb; padding: 20px; border-radius: 6px; margin-bottom: 20px;">
            <h2 style="color: #6E46AE; margin-top: 0; font-size: 20px;">{{ $task->title }}</h2>
            <p style="margin: 10px 0;"><strong>Type:</strong> {{ $task->task_type }}</p>
            @if($task->due_at)
            <p style="margin: 10px 0;"><strong>Due Date:</strong> {{ \Carbon\Carbon::parse($task->due_at)->format('F j, Y g:i A') }}</p>
            @endif
            @if($task->requisition)
            <p style="margin: 10px 0;"><strong>Related Requisition:</strong> {{ $task->requisition->job_title }}</p>
            @endif
        </div>
        
        @if($task->link)
        <div style="text-align: center; margin: 30px 0;">
            <a href="{{ url($task->link) }}" style="display: inline-block; background: #6E46AE; color: white; padding: 12px 30px; text-decoration: none; border-radius: 6px; font-weight: bold;">
                View Task
            </a>
        </div>
        @endif
        
        <p style="font-size: 14px; color: #6b7280; margin-top: 30px;">
            This is an automated notification from {{ $tenant->name ?? 'TalentLit HRMS' }}.
        </p>
    </div>
</body>
</html>


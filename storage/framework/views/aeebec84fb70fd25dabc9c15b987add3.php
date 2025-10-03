<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Contact Form Submission</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background: linear-gradient(135deg, #4F46E5, #7C3AED);
            color: white;
            padding: 20px;
            border-radius: 8px 8px 0 0;
            text-align: center;
        }
        .content {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 0 0 8px 8px;
        }
        .field {
            margin-bottom: 15px;
        }
        .field-label {
            font-weight: bold;
            color: #4F46E5;
            margin-bottom: 5px;
        }
        .field-value {
            background: white;
            padding: 10px;
            border-radius: 4px;
            border-left: 3px solid #4F46E5;
        }
        .message-content {
            background: white;
            padding: 15px;
            border-radius: 4px;
            border-left: 3px solid #4F46E5;
            white-space: pre-wrap;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>New Contact Form Submission</h1>
        <p>Someone has submitted a contact form on your website</p>
    </div>
    
    <div class="content">
        <div class="field">
            <div class="field-label">Name:</div>
            <div class="field-value"><?php echo e($name); ?></div>
        </div>
        
        <div class="field">
            <div class="field-label">Email:</div>
            <div class="field-value"><?php echo e($email); ?></div>
        </div>
        
        <div class="field">
            <div class="field-label">Company:</div>
            <div class="field-value"><?php echo e($company); ?></div>
        </div>
        
        <div class="field">
            <div class="field-label">Phone:</div>
            <div class="field-value"><?php echo e($phone); ?></div>
        </div>
        
        <div class="field">
            <div class="field-label">Subject:</div>
            <div class="field-value"><?php echo e($subject); ?></div>
        </div>
        
        <div class="field">
            <div class="field-label">Message:</div>
            <div class="message-content"><?php echo e($message); ?></div>
        </div>
    </div>
</body>
</html>
<?php /**PATH C:\xampp\htdocs\hirehub2\resources\views\emails\contact.blade.php ENDPATH**/ ?>
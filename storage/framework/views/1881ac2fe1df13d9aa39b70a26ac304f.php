<!DOCTYPE html>
<html>
<head>
    <title>New Contact Form Submission</title>
</head>
<body>
    <h1>New Contact Form Submission</h1>
    
    <p><strong>Name:</strong> <?php echo e($name); ?></p>
    <p><strong>Email:</strong> <?php echo e($email); ?></p>
    <p><strong>Company:</strong> <?php echo e($company); ?></p>
    <p><strong>Phone:</strong> <?php echo e($phone); ?></p>
    <p><strong>Subject:</strong> <?php echo e($subject); ?></p>
    <p><strong>Message:</strong></p>
    <p><?php echo e($message); ?></p>
</body>
</html>
<?php /**PATH C:\xampp\htdocs\hirehub2\resources\views\emails\contact-simple.blade.php ENDPATH**/ ?>
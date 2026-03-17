<div style="font-family: 'Helvetica', sans-serif; padding: 40px; border: 10px solid #003366;">
    <div style="text-align: center; margin-bottom: 30px;">
        <h1 style="color: #003366; margin-bottom: 5px;">MAVAZI INVENTORY</h1>
        <p style="text-transform: uppercase; font-weight: bold; color: #666; font-size: 12px;">Official Staff Credentials</p>
    </div>

    <div style="background: #f8fafc; padding: 30px; border-radius: 15px; border: 1px solid #e2e8f0;">
        <p style="margin: 10px 0;"><strong>Full Name:</strong> {{ $name }}</p>
        <p style="margin: 10px 0;"><strong>Organization Email:</strong> {{ $email }}</p>
        <hr style="border: 0; border-top: 1px dashed #cbd5e1; margin: 20px 0;">
        
        <p style="margin: 10px 0; font-size: 18px;"><strong>Staff ID (Alias):</strong> <span style="color: #003366; font-family: monospace;">{{ $alias }}</span></p>
        <p style="margin: 10px 0; font-size: 18px;"><strong>Temporary Password:</strong> <span style="color: #e11d48; font-family: monospace;">{{ $raw_password }}</span></p>
        
        <p style="margin: 10px 0;"><strong>Access Level:</strong> {{ $role }}</p>
    </div>

    <div style="margin-top: 40px; padding: 20px; background: #fffbeb; border-left: 4px solid #f59e0b;">
        <p style="font-size: 12px; color: #92400e; margin: 0;">
            <strong>SECURITY WARNING:</strong> This document contains sensitive login information. 
            Please delete this file after memorizing your credentials or changing your password.
            Generated on: {{ $generated_at }}
        </p>
    </div>
</div>
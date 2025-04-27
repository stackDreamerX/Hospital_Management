# Email Configuration Guide for Hospital Management System

## Setup Email for Appointment Notifications

To enable email sending functionality for appointment notifications, you need to configure your `.env` file with appropriate email settings. Here are different options you can use:

### Option 1: Using Mailtrap for Development/Testing

[Mailtrap](https://mailtrap.io/) is a test mail server solution that allows you to inspect emails sent from your development environment without delivering them to real recipients.

1. Sign up for a free Mailtrap account
2. Go to your inbox and get the SMTP credentials
3. Add these settings to your `.env` file:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_mailtrap_username
MAIL_PASSWORD=your_mailtrap_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=hospital@example.com
MAIL_FROM_NAME="Hospital Management System"
```

### Option 2: Using Gmail SMTP

You can use Gmail's SMTP server for sending emails:

1. Create a Gmail account or use an existing one
2. Enable "Less secure app access" or generate an "App Password" if you have 2-factor authentication enabled
3. Add these settings to your `.env` file:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your_gmail_email@gmail.com
MAIL_PASSWORD=your_gmail_password_or_app_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your_gmail_email@gmail.com
MAIL_FROM_NAME="Hospital Management System"
```

### Option 3: Using a Production Email Service (Recommended for Production)

For production, consider using a dedicated email service like:
- SendGrid
- Mailgun
- Amazon SES
- Postmark

Each has their own configuration, but they're generally more reliable for production use.

## Debugging Email Issues

If emails aren't being sent, check the following:

1. Review your Laravel logs at `storage/logs/laravel.log` for any email-related errors
2. Verify that your SMTP credentials are correct
3. Make sure your email service provider isn't blocking the connection
4. Try using the `log` driver temporarily to check email content:

```env
MAIL_MAILER=log
```

This will write emails to your log file instead of actually sending them, which is useful for debugging. 
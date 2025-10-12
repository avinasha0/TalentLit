# AI Assistant Instructions for TalentLit HRMS

## 🚫 CRITICAL: NEVER MODIFY THESE FILES
- `.env` files (any `.env.*` files)
- Production configuration files
- Database credentials
- API keys and secrets
- Payment gateway settings
- SMTP configurations

## ⚠️ ALWAYS ASK BEFORE MODIFYING
- Database migrations
- Configuration files
- Environment-related files
- Production settings
- Security-related files

## 📋 PROJECT GUIDELINES
- **Brand Name**: Always use "TalentLit" consistently
- **Color Theme**: Royal Purple (#6E46AE) and Tiffany Blue (#00B6B4)
- **Dashboard**: White backgrounds for all elements
- **File Management**: Prefer reusing existing files over creating new ones
- **Testing**: Make test cases detailed for novices
- **Database**: No schema modifications allowed for audit table

## 🔒 SECURITY REQUIREMENTS
- Never expose sensitive data in code
- Never modify production configurations
- Always validate user inputs
- Use proper authentication checks
- Respect .gitignore rules

## 📁 SENSITIVE FILES TO AVOID
- `.env` (contains production database, SMTP, Razorpay keys)
- `config/database.php`
- `config/mail.php`
- `config/services.php`
- Any file with "production" in the name
- Payment gateway configuration files

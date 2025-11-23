# Git Merge Helper Script
# Use this script to merge develop into main without editor prompts
# Usage: .\.git-merge-helper.ps1

param(
    [string]$sourceBranch = "develop",
    [string]$targetBranch = "main"
)

Write-Host "Switching to $targetBranch branch..." -ForegroundColor Cyan
git checkout $targetBranch

if ($LASTEXITCODE -ne 0) {
    Write-Host "Error: Failed to checkout $targetBranch" -ForegroundColor Red
    exit 1
}

Write-Host "Pulling latest changes from origin/$targetBranch..." -ForegroundColor Cyan
git pull origin $targetBranch

if ($LASTEXITCODE -ne 0) {
    Write-Host "Error: Failed to pull from origin/$targetBranch" -ForegroundColor Red
    exit 1
}

Write-Host "Merging $sourceBranch into $targetBranch..." -ForegroundColor Cyan
git merge $sourceBranch --no-edit

if ($LASTEXITCODE -ne 0) {
    Write-Host "Warning: Merge had conflicts or issues. Please resolve manually." -ForegroundColor Yellow
    Write-Host "After resolving, run: git commit --no-edit" -ForegroundColor Yellow
    exit 1
}

Write-Host "Merge completed successfully!" -ForegroundColor Green
Write-Host "Current branch: $targetBranch" -ForegroundColor Green
Write-Host ""
Write-Host "To push to remote, run: git push origin $targetBranch" -ForegroundColor Cyan


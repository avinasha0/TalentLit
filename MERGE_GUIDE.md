# Git Merge Guide - Avoiding Merge Issues

## Problem Fixed
Git was opening an editor (vim/notepad) for merge commit messages, causing merge operations to hang or be confusing.

## Solutions Configured

### 1. **Use the Git Alias (Recommended)**
Instead of `git merge develop`, use:
```bash
git merge-dev
```
This automatically merges develop with `--no-edit` flag, avoiding the editor prompt.

### 2. **Manual Merge with --no-edit**
Always use the `--no-edit` flag:
```bash
git checkout main
git pull origin main
git merge develop --no-edit
```

### 3. **Use the Helper Script**
Run the PowerShell helper script:
```powershell
.\.git-merge-helper.ps1
```
This script automatically:
- Switches to main branch
- Pulls latest changes
- Merges develop with --no-edit
- Shows status

## Configuration Applied

The following git configurations have been set globally:

- **Editor**: Set to `notepad` (easier than vim)
- **Merge Alias**: `git merge-dev` = `merge develop --no-edit`
- **Merge Fast-Forward**: Disabled (creates merge commits)

## Best Practices

1. **Always use `--no-edit` flag** when merging:
   ```bash
   git merge <branch> --no-edit
   ```

2. **Or use the alias**:
   ```bash
   git merge-dev
   ```

3. **Complete workflow**:
   ```bash
   # On develop branch
   git add -A
   git commit -m "Your message"
   git push
   
   # Switch to main and merge
   git checkout main
   git pull origin main
   git merge-dev  # or: git merge develop --no-edit
   git push origin main
   ```

## If Merge Conflicts Occur

1. Git will show which files have conflicts
2. Resolve conflicts in those files
3. Stage resolved files: `git add <file>`
4. Complete merge: `git commit --no-edit`

## Notes

- The `--no-edit` flag uses the default merge commit message
- This is safe and standard practice for feature branch merges
- The editor will only open if you explicitly want to edit the message


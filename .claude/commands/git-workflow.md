# Git Workflow Rules & Environment Setup

This command loads all Git workflow rules and environment settings for consistent version management across projects.

## GitHub Personal Access Token

**Location:**
```
/home/tsuyoshi/.local/share/claude/tokens/github-token
```

**Usage:**
```bash
TOKEN=$(cat /home/tsuyoshi/.local/share/claude/tokens/github-token)
git push https://${TOKEN}@github.com/USERNAME/REPO.git main
```

## Core Workflow Rules

### NEVER Update Version Without Explicit Instruction (CRITICAL)

**ABSOLUTE RULE: Do NOT update version numbers unless explicitly instructed by the user.**

- **NEVER** increment version automatically
- **NEVER** assume you should update to next version
- **NEVER** change version "to complete the workflow"
- **ONLY** update version when user explicitly says:
  - "Update to version X.Y.Z"
  - "Increment version"
  - "Release new version"
  - Or similar explicit instruction

**Examples of when NOT to update version:**
- Adding README.md, CHANGELOG.md, or other files → Stay at current version
- Fixing bugs or making changes → Stay at current version
- Completing initial push → Stay at current version
- "Finishing workflow" → Stay at current version

**The ONLY person who decides to update version is the USER.**

### 1 Version = 1 Commit (STRICT)
- Never push multiple commits for the same version
- Never use `git commit --amend` after pushing
- Never use `git push --force` (except for initial setup correction)

### Version Format
- **Files (plugin file, readme.txt, etc.)**: Always WITHOUT `-dev` suffix
  - Example: `Version: X.Y.Z` (NOT `X.Y.Z-dev`)
  - Example: `Stable tag: X.Y.Z` (NOT `X.Y.Z-dev`)
- **Git Tags**: Latest version ONLY has `-dev` suffix
  - Example: Latest tag is `vX.Y.Z-dev`
  - Previous tags are `vX.Y.Z` (no `-dev`)

### Semantic Versioning Rules

**DEFAULT: PATCH version increment only (Z +1)**
- X.Y.Z → X.Y.(Z+1)
- Examples: 1.0.0 → 1.0.1 → 1.0.2 → 1.0.12 → 1.0.13

**NEVER change MAJOR or MINOR without explicit instruction:**
- **MAJOR (X)**: Only when explicitly requested for breaking changes
- **MINOR (Y)**: Only when explicitly requested for new features
- **PATCH (Z)**: Default increment unless told otherwise

### Mandatory Files (MUST EXIST)

**Every WordPress plugin repository MUST have these files:**

1. **README.md** - Main documentation for GitHub
   - Project overview, features, installation, usage
   - Badges (version, WordPress version, PHP version, license)
   - Author information and links
   - Should include link to CHANGELOG.md
   - **NO EMOJIS ALLOWED**

2. **readme.txt** - WordPress.org format documentation
   - Required for WordPress.org plugin directory
   - Contains: Plugin Name, Contributors, Tags, Stable tag, etc.
   - Format must follow WordPress.org standards
   - **NO EMOJIS ALLOWED**

3. **CHANGELOG.md** - Version history and changes
   - Follows [Keep a Changelog](https://keepachangelog.com/) format
   - Categories: Added, Changed, Deprecated, Removed, Fixed, Security
   - Each version must have a date (YYYY-MM-DD format)
   - **NO EMOJIS ALLOWED**

**CRITICAL: These three files are MANDATORY**
- Never commit without all three files present
- Always update CHANGELOG.md when releasing a new version
- Keep README.md and readme.txt synchronized with current version
- **NEVER use emojis in README.md, readme.txt, or CHANGELOG.md**

## Initial Push Workflow

### Step 1: Initialize Repository

```bash
cd /path/to/project
git init

TOKEN=$(cat /home/tsuyoshi/.local/share/claude/tokens/github-token)
git remote add origin https://${TOKEN}@github.com/USERNAME/REPO.git
```

### Step 2: Verify Version in Files

**IMPORTANT: Files must NOT have `-dev` suffix**

Check these files have `1.0.0` (NO `-dev`):
- Plugin main file: `Version: 1.0.0`
- Plugin constant: `define( 'PLUGIN_VERSION', '1.0.0' );`
- readme.txt: `Stable tag: 1.0.0`

Example:
```php
/**
 * Version: 1.0.0
 */
define( 'PLUGIN_VERSION', '1.0.0' );
```

### Step 3: First Commit

**Commit message MUST be:**
```bash
git add -A
git commit -m "Initial release v1.0.0"
```

### Step 4: Create Tag

**Tag format: v1.0.0-dev**
```bash
git tag -a v1.0.0-dev -m "Development version 1.0.0"
```

### Step 5: Push

```bash
TOKEN=$(cat /home/tsuyoshi/.local/share/claude/tokens/github-token)
git push -u https://${TOKEN}@github.com/USERNAME/REPO.git main
git push https://${TOKEN}@github.com/USERNAME/REPO.git v1.0.0-dev
```

## Update Workflow (vX.Y.Z-dev → vX.Y.(Z+1)-dev)

**Default: Increment PATCH version only (Z → Z+1)**

### Step 1: Remove -dev from Previous Version

```bash
# Get current version from tag
CURRENT_VERSION=$(git describe --tags --abbrev=0 | sed 's/-dev$//')

# Delete -dev tag (e.g., v1.0.5-dev)
git tag -d ${CURRENT_VERSION}-dev
TOKEN=$(cat /home/tsuyoshi/.local/share/claude/tokens/github-token)
git push https://${TOKEN}@github.com/USERNAME/REPO.git :refs/tags/${CURRENT_VERSION}-dev

# Create final release tag (e.g., v1.0.5)
git tag -a ${CURRENT_VERSION} -m "Release version ${CURRENT_VERSION#v}"
git push https://${TOKEN}@github.com/USERNAME/REPO.git ${CURRENT_VERSION}
```

### Step 2: Calculate New Version

**Default calculation (PATCH +1):**

```bash
# Extract version numbers
CURRENT_VERSION="X.Y.Z"
MAJOR=$(echo $CURRENT_VERSION | cut -d. -f1)
MINOR=$(echo $CURRENT_VERSION | cut -d. -f2)
PATCH=$(echo $CURRENT_VERSION | cut -d. -f3)

# Increment PATCH only (unless instructed otherwise)
NEW_PATCH=$((PATCH + 1))
NEW_VERSION="${MAJOR}.${MINOR}.${NEW_PATCH}"  # Result: X.Y.(Z+1)
```

### Step 3: Update Version in Files

**IMPORTANT: NO `-dev` suffix in files**

Update version to `X.Y.(Z+1)` in these files:

1. **Plugin main file**
   ```php
   /**
    * Version: X.Y.(Z+1)
    */
   define( 'PLUGIN_VERSION', 'X.Y.(Z+1)' );
   ```

2. **readme.txt**
   ```
   Stable tag: X.Y.(Z+1)
   ```

### Step 4: Create New Version Commit and Tag

```bash
# Make all changes (code, docs, version numbers)

# Single commit with all changes
git add -A
git commit -m "Update to vX.Y.(Z+1)

- Feature/fix description 1
- Feature/fix description 2
- Feature/fix description 3"

# Create tag WITH -dev suffix
git tag -a vX.Y.(Z+1)-dev -m "Development version X.Y.(Z+1)"

# Push
TOKEN=$(cat /home/tsuyoshi/.local/share/claude/tokens/github-token)
git push https://${TOKEN}@github.com/USERNAME/REPO.git main
git push https://${TOKEN}@github.com/USERNAME/REPO.git vX.Y.(Z+1)-dev
```

## Version Increment Rules

### Default Behavior (NO explicit instruction)
**Always increment PATCH version (Z) only:**
- X.Y.Z → X.Y.(Z+1)

### Explicit MINOR Increment (when instructed)
**Only when user explicitly requests new feature:**
- X.Y.Z → X.(Y+1).0
- Reset PATCH to 0

### Explicit MAJOR Increment (when instructed)
**Only when user explicitly requests breaking changes:**
- X.Y.Z → (X+1).0.0
- Reset MINOR and PATCH to 0

## Files Requiring Version Updates

When updating version from X.Y.Z to X.Y.(Z+1), modify these files:

1. **Plugin main file** (`plugin-name.php`)
   ```php
   /**
    * Version: X.Y.(Z+1)
    */
   define( 'PLUGIN_VERSION', 'X.Y.(Z+1)' );
   ```

2. **readme.txt** (WordPress.org)
   ```
   Stable tag: X.Y.(Z+1)
   ```

3. **CHANGELOG.md** (if exists)
   ```markdown
   ## [X.Y.(Z+1)] - YYYY-MM-DD

   ### Added
   - New feature

   ### Changed
   - Changes

   ### Fixed
   - Bug fixes
   ```

## Pre-Commit Checklist

### For Initial Release:
- [ ] **MANDATORY FILES**: README.md, readme.txt, CHANGELOG.md all exist
- [ ] Commit message is exactly "Initial release v1.0.0"
- [ ] Tag is v1.0.0-dev (WITH `-dev`)
- [ ] All version numbers in files are 1.0.0 (WITHOUT `-dev`)
- [ ] Only ONE commit exists
- [ ] .gitignore is properly configured

### For Updates:
- [ ] **MANDATORY FILES**: README.md, readme.txt, CHANGELOG.md all updated
- [ ] CHANGELOG.md has new version entry with changes
- [ ] Previous -dev tag removed from remote
- [ ] Previous final tag created (WITHOUT `-dev`)
- [ ] All version numbers in files updated to X.Y.(Z+1) (WITHOUT `-dev`)
- [ ] Commit message is "Update to vX.Y.(Z+1)"
- [ ] Only ONE new commit for this version
- [ ] New tag is vX.Y.(Z+1)-dev (WITH `-dev`)
- [ ] PATCH version incremented by 1 (unless explicitly instructed otherwise)

## Summary: -dev Usage

**CRITICAL RULE:**

| Location | Format | Example |
|----------|--------|---------|
| **Plugin file header** | `Version: X.Y.Z` (NO `-dev`) | `Version: X.Y.Z` |
| **Plugin constant** | `'X.Y.Z'` (NO `-dev`) | `'X.Y.Z'` |
| **readme.txt** | `Stable tag: X.Y.Z` (NO `-dev`) | `Stable tag: X.Y.Z` |
| **Git tag (latest)** | `vX.Y.Z-dev` (WITH `-dev`) | `vX.Y.Z-dev` |
| **Git tag (released)** | `vX.Y.Z` (NO `-dev`) | `vX.Y.Z` |

## Prohibited Actions

Never do these:
1. **Updating version without explicit user instruction** (MOST CRITICAL)
2. **Using emojis in README.md, readme.txt, or CHANGELOG.md** (CRITICAL)
3. Multiple commits for same version
4. `git commit --amend` after push
5. `git push --force` (except initial correction)
6. Adding `-dev` to version numbers in files
7. Changing MAJOR or MINOR without explicit instruction
8. Incrementing by more than +1 for PATCH without instruction
9. Forgetting `-dev` suffix on latest git tag
10. Reusing/recreating tags (except -dev migration)
11. Committing without all mandatory files (README.md, readme.txt, CHANGELOG.md)

## Quick Reference Commands

```bash
# Load token
TOKEN=$(cat /home/tsuyoshi/.local/share/claude/tokens/github-token)

# Check current state
git log --oneline
git tag -l

# Get current version from tag
git describe --tags --abbrev=0

# Delete remote tag
git push https://${TOKEN}@github.com/USERNAME/REPO.git :refs/tags/TAG_NAME

# Create and push tag
git tag -a vX.Y.Z-dev -m "Development version X.Y.Z"
git push https://${TOKEN}@github.com/USERNAME/REPO.git vX.Y.Z-dev
```

---

**Git configuration:**
```bash
git config user.name "Tsuyoshi Kashiwazaki"
git config user.email "t.kashiwazaki@contencial.co.jp"
```

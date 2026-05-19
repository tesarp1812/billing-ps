# ~/.zshrc - Clean Fullstack Developer Setup (MacBook)

# ========================================
# Homebrew
# ========================================
eval "$(/opt/homebrew/bin/brew shellenv)"

# ========================================
# MySQL
# ========================================
export PATH="/usr/local/mysql/bin:$PATH"

# ========================================
# PHP 8.2 (Default)
# ========================================
export PATH="/opt/homebrew/opt/php@8.2/bin:$PATH"
export PATH="/opt/homebrew/opt/php@8.2/sbin:$PATH"

alias php8.2="/opt/homebrew/opt/php@8.2/bin/php"

# ========================================
# Composer
# ========================================
alias composer="/opt/homebrew/bin/composer"

# ========================================
# Node Version Manager (NVM)
# ========================================
export NVM_DIR="$HOME/.nvm"
[ -s "$NVM_DIR/nvm.sh" ] && \. "$NVM_DIR/nvm.sh"
[ -s "$NVM_DIR/bash_completion" ] && \. "$NVM_DIR/bash_completion"

# ========================================
# Custom Tools (Optional)
# ========================================
export PATH="/Users/tesarpratama/.antigenvirity/antigravity/bin:$PATH"

# ========================================
# Useful Aliases
# ========================================
alias ll="ls -lah"
alias gs="git status"
alias ga="git add ."
alias gc="git commit -m"
alias gp="git push"
alias artisan="php artisan"

# ========================================
# END
# ========================================
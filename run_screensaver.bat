@echo off
echo Starting Screen Saver Application...
python screensaver.py
if %errorlevel% neq 0 (
    echo.
    echo Python is not installed or not in PATH!
    echo Please install Python from: https://www.python.org/downloads/
    echo Make sure to check "Add Python to PATH" during installation
    pause
)

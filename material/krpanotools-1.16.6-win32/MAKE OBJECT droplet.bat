@echo off
echo MAKE OBJECT droplet

IF "%~1" == "" GOTO ERROR
IF NOT EXIST "%~1" GOTO ERROR

"%~dp0\kmakemultires" "%~dp0\templates\object.config" %*
GOTO DONE

:ERROR
echo.
echo Drag and drop all object images on thie droplet to create 
echo automatically a multiresolution object movie from it.

:DONE
echo.
pause

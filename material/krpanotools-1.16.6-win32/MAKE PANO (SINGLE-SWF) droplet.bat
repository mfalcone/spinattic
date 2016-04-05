@echo off
echo MAKE PANO (SINGLE-SWF) droplet

IF "%~1" == "" GOTO ERROR
IF NOT EXIST "%~1" GOTO ERROR

"%~dp0\kmakemultires" "%~dp0\templates\singleswf.config" %*
GOTO DONE

:ERROR
echo.
echo Drag and drop panoramic images to create automatically 
echo single swf files from it.

:DONE
echo.
pause

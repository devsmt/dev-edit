@ECHO OFF
REM --------------------------------------------------------------------------
REM  scoppo del programma
REM --------------------------------------------------------------------------

REM Make environment variable changes local to this batch file
SETLOCAL

REM Make cwRsync home as a part of system PATH to find required DLLs
SET CWOLDPATH=%PATH%
SET PATH=%CWRSYNCHOME%\BIN;%PATH%

REM Set CYGWIN variable to 'nontsec'. That makes sure that permissions
REM on your windows machine are not updated as a side effect of cygwin
REM operations.
SET CYGWIN=nontsec

set /P env="quale server aggiorno? (prod,test):" : %=%
set /P dryrun="scrivo definitivamente in remoto? (--go): " : %=%

php sync.php %env% %dryrun%
pause

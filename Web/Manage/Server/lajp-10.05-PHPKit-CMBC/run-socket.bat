@echo off

rem -----------------------------------------------------------
rem  LAJP-Java Socket Service �����ű� 
rem		
rem 		(2009-10 http://code.google.com/p/lajp/)
rem  
rem -----------------------------------------------------------

rem java��������Ҫ��jar�ļ���classpath·������ҵ����򡢵�����jar�ļ�log4j��
setlocal enabledelayedexpansion 
set CLASSPATH=.
 for %%j in (.\lib\*.jar) do (
   set CLASSPATH=!CLASSPATH!;%%j
 )

rem �Զ�������ͷ�����LAJP��������ʱ���Զ����ز�ִ��
rem set AUTORUN_CLASS=com.foo.AutoRunClass
rem set AUTORUN_METHOD=AutoRunMethod

rem �ַ�������  GBK | UTF-8
rem set CHARSET=UTF-8

rem LAJP��������ָ��
java -classpath .;%classpath% lajpsocket.PhpJava

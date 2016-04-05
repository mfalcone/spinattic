!*******************************************************************************************
!*************************Where is Wally?***************************************************
!*******************************************************************************************
!	Programa para encriptar datos del tipo ASCII para sms, Twiter o Facebook
!	usando el algoritmo: Where is Wally?
!
!	Concepto:
!	El algoritmo se basa en la dificultad de encontrar numeros
!	aleatorios escondidos en secuencias de numeros pseudo-aleatorios.
!       Cada nuevo mensaje esta encriptado usando una nueva clave elegida
!	aleatoriamente pero escondida dentro del mensaje encriptado.
!	La llave indica la posicion de la clave en el mensaje encriptado.
!
!	Numeros aleatorios:
!	Se obtienen del tiempo de reaccion del usuario al escribir secuencias
!	cortas de caracteres pseudo-aleatorios.
!	
!	Encriptacion:
!	Cada caracter se reemplaza por un numero. A cada numero se le suma otro numero
!	pseudo-aleatorio generado por una cadena de Markov. Se permutan de ida y de vuelta
!	las posiciones de los caracteres eligiendo las posiciones a permutar de acuerdo a
!	una serie de numeros pseudo-aleatorio generado por la misma cadena de Markov.
!
!	Ventajas:
!	Cada mensaje esta encriptado con una nueva clave aleatoria.
!	Los ataques estadisticos no tienen mucho sentido.
!	Los mensajes encriptados se ven como caracteres aleatorios.
!	Se podria usar para encriptar mensajes en el ruido de imagenes o sonido.
!	Romper la llave por fuerza bruta es dificil: Llaves posibles=160*159*...145=84.9E+33
!	El mensaje encriptado ocupa casi lo mismo que el original.
!	La desencriptacion tolera bastante bien los errores en la transmicion de informacion.
!	Dos llaves muy parecidas dan resultados totalmente distintos.
!
!	Desventajas:
!	La generacion de numeros aletorios puede ser tediosa para el usuario.
!	Las llaves son dificiles de recordar.
!
!********************************************************************************************
!	Parece andar todo bien 17/01/2012!!
!		Probado:
!	a) Cambiando un caracter, el mensaje original se recupera
!	    casi intacto con alta probabilidad.	
!	b) Cambiando un lugar de la llave, el mensaje original
!           se ve cualquiera!
!	c) Solucionado problema de Ran2 los primeros numeros son parecidos 
!	d) Solucionado problema tiempos en milesimas correlacionados
!	Se maquilla todo un poco encriptando la clave
!	
!	Test
!	1) Histogramas 1 D (ok)
!	2) Histogramas 2 D (ok)
!	3) Hitogramas posicion-caracter (ok)
!	4) Histogramas 1D y 2D Markov y pass (ok)
!	5) 1 lugar distinto da cualquiera. 1 dato distintos se entiende
!	igual, 1 dato distinto en la llave da cualquiera.
!	Si hay un dos lugares cambiados de lugar en key existe la
!	posibilidad de que salga el mismo pass para los dos y entonces
!	eL mensaje se desencripte igual. Esto se podria usar para facilitar la ruptura de la
!	desencriptacion. Igual sacando cuentas con Nkey=10,
!	es terriblemente dificil descubrir la llave.
!	Sacando los mensajes desriptados por azar. El resto no parece tener
!	correlaciones muy obvias, al menos usando "histo-01.for"
!	Evitando que pass(i) y pass(j) sean iguales hace muy facil
!	encontrar los lugares de key.
!	
!**************************************************************************
	program WIW01
	implicit none
	integer Nkey,Nsms,Nin,Min,Max
	parameter (Nsms =   160  ,   Nkey=  16)	!longitud sms y longitud de la llave
	parameter (Nin=Nsms-Nkey)		!longitud mensaje
	parameter (Min=32,Max=126)		!Solo ASCII imprimibles
!****
	integer input(Nin),key(Nkey),pass(Nkey),error
	integer i,j,Nfmtin,delta(Nin),output(Nsms)
	logical Enc
	character (Len=1) ED
	character (Len=5) borrar
!***************************************************************

! 	write(*,*)
!5 	write(*,*)"    Linux o Windows  (L/W)"
! 	read(*,*)ED
	ED="L"
	if (ED.eq."l".or.ED.eq."L") then
         borrar="clear"			!Windows
        elseif (ED.eq."w".or.ED.eq."W") then
         borrar="cls"			!Linux
!        else
!         goto 5
        end if
 	
10	call system(borrar)
 	write(*,*)"*************************************"
 	write(*,*)"Where is Wally? (version 0.1.2 Linux)"
 	write(*,*)"*************************************"
	write(*,*)"	Generar llave    (g/G)"
	write(*,*)"	Introducir llave (i/I)"
	write(*,*)"	Salir            (s/S)"
	read(*,*)ED
!***
	if (ED.eq."g".or.ED.eq."G") then	!Generar llave
	 call generator01(Nkey,Nin,Key,1,Nsms,borrar,.false.)
	elseif (ED.eq."i".or.ED.eq."I") then	!Introducir llave
	 call KeyReader(Nkey,Nsms,Key,borrar)
	elseif (ED.eq."s".or.ED.eq."S") then	!salir
	 stop "Programa terminado por usuario"
	else
	 write(*,*)"Opcion invalida!!"
	 write(*,*)
	 write(*,*)"Pulse enter para continuar"
	 read(*,*)
	 goto 10
	end if
!***************************************************************
20	call system(borrar)
	write(*,*)"Encriptar    (e/E)"
	write(*,*)"Desencriptar (d/D)"
	write(*,*)"Nueva llave  (n/N)"
	write(*,*)"Salir        (s/S)"
	read(*,*)ED
!***
	if (ED.eq."e".or.ED.eq."E") then 	!encriptar
	 Enc=.true.
	 Nfmtin=1
	 do while ((Nin-10**Nfmtin).ge.0)	
	 Nfmtin=Nfmtin+1			!cifras significativas de Nin
	 end do
	 call reader(Nin,Nfmtin,input,Min,Max,borrar,Enc)	!lee mensaje
	 call generator01(Nkey,Nin,pass,Min,Max,borrar,Enc)		!genera la clave
	 call enigma01(Nkey,Nin,input,pass,output,Min,Max,Enc)	!enigmatiza el mensaje
	 call mixer01(Nkey,Nin,pass,output,Enc)			!mezcla el mensaje
	 call insert01(Nkey,Nin,key,pass,output,Enc)		!inserta la clave
	 call salida(Nkey,Nin,input,output,pass,Key,Min,Max,borrar,Enc)
	elseif (ED.eq."d".or.ED.eq."D") then	!desencriptar
	 Enc=.false.
	 Nfmtin=1
	 do while ((Nsms-10**Nfmtin).ge.0)	
	 Nfmtin=Nfmtin+1			!cifras significativas de Nsms
	 end do
	 call reader(Nsms,Nfmtin,output,Min,Max,borrar,Enc)	!lee mensaje encriptado
	 call insert01(Nkey,Nin,key,pass,output,Enc)		!recupera la clave insertada
	 call mixer01(Nkey,Nin,pass,output,Enc)			!Ordena el mensaje
	 call enigma01(Nkey,Nin,input,pass,output,Min,Max,Enc)	!des-enigmatiza el mensaje
	 call salida(Nkey,Nin,input,output,pass,Key,Min,Max,borrar,Enc)
	elseif (ED.eq."n".or.ED.eq."N") then	!salir
	 goto 10
	elseif (ED.eq."s".or.ED.eq."S") then	!salir
	 stop "Programa terminado por usuario"
	else
	 write(*,*)"Opcion invalida!!"
	end if
	 goto 20
	end program

!
!	Lee la "llave", o sea la ubicación de la clave en el mensaje.
!	Son números entre 1 y Nsms (el largo del sms)
!
	subroutine KeyReader(Nkey,Nsms,Key,borrar)
	implicit none
	integer i,j,Nkey,key(Nkey),error,Nsms
	character (Len=5) borrar
!*******
	call system(borrar)
10	write(*,1000)'Introduzca los',Nkey,' numeros entre 1 y'
     &,nsms,' separados por espacios.'
1000	format(A14,I4,A18,I4,A24)
	 read(*,*,iostat=error)(key(i),i=1,Nkey)
	 if (error.ne.0)	then	!Chequeo 1
	  write(*,*)"Error de lectura de la llave"
	  write(*,*)
	  goto 10
	 end if
	do i=1,Nkey			!Chequeo 2
	 if (key(i).lt.1.or.key(i).gt.Nsms) then
	  write(*,*)"Llave fuera del intervalo"
	  write(*,*)"key(i),i=",key(i),i
	  write(*,*)
	  goto 10
	 end if
	end do
	do i=1,Nkey-1			!Chequeo 3
	 do j=i+1,Nkey
  	  if (key(i).eq.key(j)) then
	  write(*,*)"No se pueden repetir numeros en la llave"
	  write(*,*)"Key(",i,") = Key(",j,") =",key(i)
	  write(*,*)
	  goto 10
	  end if
	 end do
	end do
	 write(*,*)"***********"
	 write(*,*)"Llave leida"
	 write(*,1001)(Key(i),i=1,Nkey)
	 write(*,*)"***********"
	 write(*,*)
	 write(*,*)"Pulse enter para continuar"
	 read(*,*)
1001	format(100I4)
	end subroutine

!
!	Lee una cadena de caracteres de largo arbitrario
!	la transforma en numeros
!	Nin largo del mensaje
!	Nfmtin cifras significativas de Nin
!	input(i) Mensaje en numeros enteros entre Min y Max
!
	subroutine reader(Nin,Nfmtin,input,Min,Max,Borrar,Enc)
	implicit none
	integer i,j,error,Nin,Nfmtin,M
	integer input(Nin),N(Nfmtin),Min,Max
	character (Len=Nin) Cinput
	character (Len=Nfmtin+3) fmtin
	LOGICAL Enc
	character (Len=1) ED
	character (Len=5) borrar
!**************define el formato de entrada**********************
	M=Nin
	do j=Nfmtin,1,-1	!separa Nin en sus decimales
	N(j)=0
	do while (M-N(j)*10**(j-1).ge.0)
	N(j)=N(j)+1
	end do
	N(j)=N(j)-1
	M=M-N(j)*10**(j-1)
	end do
	fmtin="(A"		!construye el formato
	do j=Nfmtin,1,-1
	 fmtin=trim(fmtin)//achar(48+N(j))
	end do
	 fmtin=trim(fmtin)//")"
!**************lee el mensaje**********************
	call system(borrar)
10	write(*,*)"Escriba el mensaje. Numero maximo de caracteres",Nin
	read(*,fmtin)Cinput
!************Transforma en numeros****************
	do i=1,Nin
	 input(i)=iachar(Cinput(i:i+1))
	 if (input(i).lt.Min.or.input(i).gt.Max) then
	  write(*,*)"Caracter no valido como input!"
	  write(*,*)"Posicion",i," numero ASCII=",input(i)
	  write(*,*)
	  goto 10
	  return
	 end if
	end do
	write(*,*)
	write(*,*)"Mensaje leido --> ... <--"
	write(*,*)'-->',Cinput,'<--'
	write(*,*)
	write(*,*)'Reescribir?  Si (s/S), No (otro)'
	read(*,*)ED
	if (ED.eq."s".or.ED.eq."S") goto 10
	end subroutine

!
!	Genera la clave => una serie de caracteres aleatorios (Nkey caracteres)
!	(o Nkey numeros aleatorios entre 32 y 126)
!
!	utiliza: (tiempo de la CPU + interaccion con el usuario
!	+ ran2) para generar una "True Random Chain"
! Se repiten los primneros caracteres
!	Enc=.false. Esta generando la llave! 
	subroutine generator01(Nkey,Nin,pass,Min,Max,Borrar,Enc)
	implicit none
	integer Nkey,Nin,Min,Max,i,j,k
	integer input(Nin),output(Nin+Nkey),pass(Nkey),passDum(1)
	logical Enc
	character (Len=5) borrar
!******************************************************************
!**Genera numero true random (o casi)
	call generator02(Min,Max,Nkey,pass,borrar)
!**Maquillaje final	!autoencriptar la clave
	call markov(pass,Nkey,input,Nin,Min,Max)
	call enigma01(Nkey,Nin,input,pass,output,Min,Max,.true.)
	call mixer01(Nkey,Nin,pass,output,.true.)
	do i=1,Nkey
	pass(i)=output(i)
	end do

	if (Enc) then		!Esta encriptando
	 return
	else			!Esta generando la llave
20	  do i=1,Nkey-1
	  do j=i+1,Nkey
  	   if (pass(i).eq.pass(j)) then		!Dos iguales?
	   call system(borrar)
	   call generator02(Min,Max,1,passDum,borrar)	!Genera 1 true random
	   pass(j)=passDum(1)			!Reemplaza
	   goto 20				!De nuevo probar todo
	   end if
	  end do
	  end do
	call system(borrar)
	 write(*,*)"*****************"
	 write(*,*)"Memorize la llave"
	 write(*,1000)(pass(i),i=1,Nkey)
	 write(*,*)"*****************"
	 write(*,*)
	 write(*,*)"Pulse enter para continuar"
	 read(*,*)
1000	 format(100I4)
	end if
	end subroutine

	subroutine generator02(Min,Max,Nkey,pass,borrar)
	implicit none
	integer Min, Max,Nkey,pass(Nkey)
	integer i,j,idum,Ntry,Ntest,min2,max2
	parameter (Ntest=3)
	character (len=1) try(Ntest),test(Ntest)
	character (8) date
	character (10) time
	character (5) zone 
	integer value(8)
	real RAN2,rdum
	character (Len=5) borrar
!*******************************************
!	97 ="a"	122="z"
!	48="0"	57="9"
!	value(1)=año  value(2)=mes value(3)=dia value(4)=zona
!	value(5)=hora value(6)=min value(7)=seg value(8)=milesimas
!**Inicializacion
	call DATE_AND_TIME(date, time, zone, value)
	Idum=value(6)+value(7)+value(8)
	Min2=97
	Max2=122
	do i=1,30	!Ran2 es una bosta y los primeros ran2 son iguales
	Rdum=RAN2(IDUM)
	end do
	do i=1,Nkey
!**genera letras aleatoriamente
	Rdum=RAN2(IDUM)
	if     (Rdum.lt.1./real(Ntest)) then
	Ntry=1
	elseif (Rdum.lt.2./real(Ntest)) then
	Ntry=2
	elseif (Rdum.lt.3./real(Ntest)) then
	Ntry=3
	else
	stop "mal Rdum"
	end if
	 do j=1,Ntry	
	 rdum=Min2+RAN2(IDUM)*(Max2-Min2+1)
	 if (RAN2(IDUM).lt.0.5) rdum=rdum-32	!mayusculas
	 test(j)=achar(int(rdum))
	 end do	
!**Interacciona con el usuario
10	call system(borrar)
       write(*,1001)" Tiempo: ",value(6)," min ",value(7),","
     &,value(8)," seg"
1001    format(A9,I2,A5,I2,A1,I3,A4)
	if (Nkey.eq.1) then
	write(*,*)"Dos llaves iguales. Corrigiendo"
	else
	write(*,*)'Generando llave',i,' de',Nkey
	end if
	write(*,*)"Por favor escriba las letras que ",
     &"se muestran a continuacion:"
	write(*,1002)(test(j),j=1,Ntry)
1002	format(100A2)	
	read(*,*)(try(j),j=1,Ntry)
	 do j=1,Ntry
	 if (try(j).ne.test(j)) goto 10	
	 end do
!**Obtiene "true-random numbers" (o casi) por el tiempo de reaccion
	call DATE_AND_TIME(date, time, zone, value)
	pass(i)=value(6)+value(7)+value(8)
	 do while (pass(i).lt.Min)
	 pass(i)=pass(i)+(Max-Min+1)
	 end do
	 do while (pass(i).gt.Max)
	 pass(i)=pass(i)-(Max-Min+1)
	 end do
	end do
	end subroutine



!		(Inspirado en la máquina enigma)
!	Transforma el mensaje para que parezca aleatorio usando
!	la clave.
!
	subroutine enigma01(Nkey,Nin,input,pass,output,Min,Max,Enc)
	implicit none
	logical Enc
	integer Nkey,Nin,Min,Max
	integer i,delta(Nin),input(Nin),pass(Nkey),output(Nin+Nkey)
!********
	call markov(pass,Nkey,delta,Nin,Min,Max)
	if (Enc) then	!encriptar
	 do i=1,Nin
	 output(i)=input(i)+delta(i)
	 do while (output(i).lt.Min)
	 output(i)=output(i)+(Max-Min+1)
	 end do
	 do while (output(i).gt.Max)
	 output(i)=output(i)-(Max-Min+1)
	 end do
	 end do
	else		!desencriptar
	 do i=1,Nin
	 input(i)=output(i)-delta(i)

	 do while (input(i).lt.Min)
	 input(i)=input(i)+(Max-Min+1)
	 end do
	 do while (input(i).gt.Max)
	 input(i)=input(i)-(Max-Min+1)
	 end do
	 end do
	end if
	end subroutine

!		(Inspirado en la serie de Fibonacci)
!	Genera una cadena de Markov de numeros entre Max y Min (delta)
!	de Nin miembros, usando Nkey numeros semilla (pass).
!	Con pass(1)=1, pass(2)=1, Nkey=2, Min=1, Max=grande
!	genera la serie de Fibonacci
!
	subroutine Markov(pass,Nkey,delta,Nin,Min,Max)
	implicit none
	integer Nkey,Nin,Min,Max,Ndum
	integer i,j,pass(Nkey),delta(Nin)
	if (Max.le.Min) stop "mal Min o Max"
	Ndum=nkey
	if (Nkey.gt.Nin) Ndum=Nin
	do i=1,Ndum
	delta(i)=pass(i)
	 do while (delta(i).lt.Min)
	 delta(i)=delta(i)+(Max-Min+1)
	 end do
	 do while (delta(i).gt.Max)
	 delta(i)=delta(i)-(Max-Min+1)
	 end do
	end do
	do i=Nkey+1,Nin
	 delta(i)=0
	 do j=i-Nkey,i-1
	 delta(i)=delta(i)+delta(j)
	 end do
	 do while (delta(i).lt.Min)
	 delta(i)=delta(i)+(Max-Min+1)
	 end do
	 do while (delta(i).gt.Max)
	 delta(i)=delta(i)-(Max-Min+1)
	 end do
	end do
	end subroutine

!
!	Mezcla los numeros para ocultar las correlaciones	
!	Usa la cadena de markov de atras para adelante
!	para ocultar correlaciones enigma-mixer
!	
	subroutine mixer01(Nkey,Nin,pass,output,Enc)
	implicit none
	logical Enc
	integer Nkey,Nin,Dummy
	integer i,j,k,pass(Nkey),output(Nin+Nkey),delta(Nin)
!********
	call Markov(pass,Nkey,delta,Nin,1,Nin)
	if (Enc) then	!encriptar
	 do i=1,Nin
	 j=i+delta(Nin-(i-1))	!Markov de atras para adelante
	 if (j.gt.Nin) j=j-Nin
	 Dummy=output(j)
	 output(j)=output(i)
	 output(i)=Dummy
	 end do
	 do i=1,Nin
	 j=i+delta(i)		!Markov de adelante para atras
	 if (j.gt.Nin) j=j-Nin
	 Dummy=output(j)
	 output(j)=output(i)
	 output(i)=Dummy
	 end do
	else		!desencriptar
	 do i=Nin,1,-1
	 j=i+delta(i)		!Markov de adelante para atras
	 if (j.gt.Nin) j=j-Nin
	 Dummy=output(j)
	 output(j)=output(i)
	 output(i)=Dummy
	 end do
	 do i=Nin,1,-1
	 j=i+delta(Nin-(i-1))	!Markov de atras para adelante
	 if (j.gt.Nin) j=j-Nin
	 Dummy=output(j)
	 output(j)=output(i)
	 output(i)=Dummy
	 end do
	end if
	end subroutine

!
!	Inserta la clave en la posición dada por la llave
!
	subroutine insert01(Nkey,Nin,key,pass,output,Enc)
	implicit none
	logical Enc
	integer i,j,Nkey,Nin
	integer key(Nkey),pass(Nkey),key2(Nkey),pass2(Nkey)
	integer output(Nin+Nkey)
!*******
	if (Enc) then	!encriptar
	 call ordenar(Nkey,key,pass,key2,pass2)	!ordeno la llave/clave
	 do i=1,NKey
	  do j=Nin+Nkey-1,Key2(i),-1		!hago espacio
	  output(j+1)=output(j)
	  end do	
	 output(Key2(i))=pass2(i)		!inserto la clave
	 end do
	else		!desencriptar
	 do i=1,NKey
	 pass(i)=output(Key(i))		!extraigo la clave
	 end do
	 call ordenar(Nkey,key,pass,Key2,Pass2)	!ordeno la llave unicamente
	 do i=1,NKey
	  do j=Key2(i)-(i-1),Nin+Nkey-1		!reacomodo el mensaje
	  output(j)=output(j+1)
	  end do	
	 end do
	end if
1000	format(100I4)
	end subroutine	

	subroutine ordenar(Nkey,key,pass,key2,pass2)
	implicit none
	integer Nkey,dum,i,j
	integer Key(Nkey),pass(Nkey),Key2(Nkey),pass2(Nkey)
	do i=1,Nkey
	key2(i)=key(i)
	pass2(i)=pass(i)
	end do
	do i=1,Nkey-1
	 do j=i+1,Nkey
	 if (Key2(j).lt.key2(i)) then
	  dum=key2(i)
	  key2(i)=key2(j)
	  key2(j)=dum
	  dum=pass2(i)
	  pass2(i)=pass2(j)
	  pass2(j)=dum
	 end if
	 end do
	end do
	end subroutine

	subroutine salida(Nkey,Nin,input,output,pass,Key,Min,Max
     &,borrar,Enc)
	implicit none
	integer i,Nkey,Nin,Min,Max,error
	integer input(Nin),output(Nkey+Nin),pass(Nkey),key(Nkey)
	character (Len=Nkey+Nin) Aout
	character (Len=Nin) Ain
	logical Enc
	character (Len=5) borrar
	character (8) date
	character (10) time
	character (5) zone
	integer value(8)
!*******************************************************
	do i=1,Nkey+Nin
	if (i.le.Nin)  Ain(i:i+1)=achar(input(i))
	Aout(i:i+1)=achar(output(i))
	end do
	call system(borrar)
	if (Enc) then	!Encriptar
	 write(*,1001)"Llave:"
	 write(*,1000)(Key(i),i=1,Nkey)
!	 write(*,*)"Clave --> ... <--"
!	 write(*,1002)" ","-","-",">",(achar(pass(i)),i=1,Nkey)
!     &,"<","-","-"
	 write(*,*)"Mensaje original --> ... <--"
	 write(*,*)"-->",Ain,"<--"
	 write(*,*)"Mensaje encriptado --> ... <--  "
     &,"Guardado en WIW-0-1-2.txt"
	 write(*,*)"-->",Aout,"<--"
         open(10,file="WIW-0-1-2.txt")
10       read(10,*,end=20)
         goto 10
20       continue
         call DATE_AND_TIME(date, time, zone, value)
         write(10,1004)" Fecha: ",date," Hora: ",time
         write(10,1003)Aout
         close(10)
	else
	 write(*,1001)"Llave:"
	 write(*,1000)(Key(i),i=1,Nkey)
!	 write(*,*)"Clave --> ... <--"
!	 write(*,1002)" ","-","-",">",(achar(pass(i)),i=1,Nkey)
!     &,"<","-","-"
	 write(*,*)"Mensaje desencriptado --> ... <--"
	 write(*,*)"-->",Ain,"<--"
	end if
	write(*,*)
	write(*,*)"Pulse enter para continuar"
	read(*,*)
1000	format(100I4)
1001	format(1A6)
1002	format(100A1)
1003    format(A160)
1004     format(A8,A8,A7,A10)
	end subroutine

      FUNCTION ran2(idum)
      INTEGER idum,IM1,IM2,IMM1,IA1,IA2,IQ1,IQ2,IR1,IR2,NTAB,NDIV
      REAL ran2,AM,EPS,RNMX
      PARAMETER (IM1=2147483563,IM2=2147483399,AM=1./IM1,IMM1=IM1-1
     &,IA1=40014,IA2=40692,IQ1=53668,IQ2=52774,IR1=12211,IR2=3791
     &,NTAB=32,NDIV=1+IMM1/NTAB,EPS=1.2e-7,RNMX=1.-EPS)
      INTEGER idum2,j,k,iv(NTAB),iy
      SAVE iv,iy,idum2
      DATA idum2/123456789/, iv/NTAB*0/, iy/0/
      if (idum.le.0) then
        idum=max(-idum,1)
        idum2=idum
        do 11 j=NTAB+8,1,-1

          k=idum/IQ1
          idum=IA1*(idum-k*IQ1)-k*IR1
          if (idum.lt.0) idum=idum+IM1
          if (j.le.NTAB) iv(j)=idum
11      continue
        iy=iv(1)
      endif
      k=idum/IQ1
      idum=IA1*(idum-k*IQ1)-k*IR1
      if (idum.lt.0) idum=idum+IM1
      k=idum2/IQ2
      idum2=IA2*(idum2-k*IQ2)-k*IR2
      if (idum2.lt.0) idum2=idum2+IM2
      j=1+iy/NDIV
      iy=iv(j)-idum2
      iv(j)=idum
      if(iy.lt.1)iy=iy+IMM1
      ran2=min(AM*iy,RNMX)
      return
      END


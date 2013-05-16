#Librerie
#import RPi.GPIO as GPIO
import urllib2
import time
import json
import random
#Setting up GPIO notation
#GPIO.setmode(GPIO.BCM)

#Defining MUX's GPIO (Multiplexer Out)
MUXIO = 22
#Defining ALE's GPIO (ALE= Address Latch Enable)
ALE     = 0
BASEURL = "http://mustached-pi.alfioemanuele.it/fresta"
SID   	= "123456"

#Actives ALE (impulsive trigger)
def aleOn():
	GPIO.output(ALE, GPIO.HIGH)
	GPIO.output(ALE, GPIO.LOW)

def read():
	GPIO.setup(MUXIO, GPIO.IN)
	return GPIO.input(MUXIO)

def pulse():
	GPIO.setup(MUXIO, GPIO.OUT)
	GPIO.ouput(MUXIO, GPIO.HIGH)
	time.sleep(0.01)
	GPIO.output(MUXIO, GPIO.LOW)

#Converts port's address from dec to bin and set the GPIO to the right logical level, than activates ALE
def setPort(port):
	portb=bin(port)
	#D -> MSB
	if portb[2]==1:
		GPIO.output(21,GPIO.HIGH)
	else:
		GPIO.output(21,GPIO.LOW)
	#C
	if portb[3]==1:
		GPIO.output(17,GPIO.HIGH)
	else:
		GPIO.output(17,GPIO.LOW)
	#B
	if portb[4]==1:
		GPIO.output(4,GPIO.HIGH)
	else:
		GPIO.output(4,GPIO.LOW)
	#A -> LSB
	if portb[5]==1:
		GPIO.output(1,GPIO.HIGH)
	else:
		GPIO.output(1,GPIO.LOW)
	#Activating ALE...
	aleOn()




#lettura codice univoco dispositivo
#fi=open('MID.txt', 'r')
#MID=fi
#Richiesta iniziale -> Invia il codice univoco e riceve un array coi valori desiderati dei sensori
#azzeramanto arrayprec
arrayprec = {}
																			#modificare MID
f=urllib2.urlopen(BASEURL + '/endpoint.php', json.dumps({'sid':SID}))
arrayconf=f.read()
print arrayconf
arrayconf=json.loads(arrayconf)
#ciclo infinito
while 1:
#Ciclo -> Controlla le porte dall'array ricevuto all'inizio/al ciclo prec., salva i valori dei sensori e lo stato delle porte
#-> dopo averlo cambiato se differente dalla configurazione.
#->Invia l'array risultato e ottiene come risposta un array di configurazione aggiornato.
#-> Cicla all'infinito <3

	#inizializza un'array
	arrayread={}
	print arrayconf['ports']
	#controlla l'array ricevuto dal server
	for (port, value) in arrayconf['ports'].iteritems():
		#porta il mux alla porta giusta
		#setPort(port)
		#Case output
		if value["type"]=="output":
			if value["value"]!=arrayprec.get(port, 0):
				# pulse()
				print "pulse"
				arrayprec[port]=value["value"]
		#Case input
		if value["type"]=="input":
			#arrayread[port]=read()
			temp = random.randint(0,255)
			arrayread[port]=temp

	#Converte l'array in json
		#sistemare MID
	arrayread={'sid': SID, 'ports': arrayread}
	arrayread=json.dumps(arrayread)
	#invia l'array con un post
	f=urllib2.urlopen( BASEURL + '/endpoint.php',arrayread)
	#legge la risposta HTTP
	arrayconf=f.read()
	print arrayconf
	#Converte il json in un array
	arrayconf=json.loads(arrayconf)
	time.sleep(1)

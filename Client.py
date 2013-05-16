#Libraries
import RPi.GPIO as GPIO
import urllib2
import time
import json
import random

#Setting up GPIO notation
GPIO.setmode(GPIO.BCM)

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

#read value from ports set as input
def read():
	GPIO.setup(MUXIO, GPIO.IN)
	return GPIO.input(MUXIO)

#give an impulsive signal to MUX'S out -> changes flip flop's state
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




#Reads MID (Machine ID) from client
fi=open('MID.txt', 'r')
MID=fi

#Sets to 0 the array which contains the previous configuration
arrayprec = {}

#Send MID and ask for a configuration array
f=urllib2.urlopen(BASEURL + '/endpoint.php', json.dumps({'sid':MID}))
arrayconf=f.read()
arrayconf=json.loads(arrayconf)

#infinite loop
while 1:
#Loop -> changes the switch value if different from the previous configuration
#     -> saves values from sensors

	#creates new array
	arrayread={}
	print arrayconf['ports']
	#Checks the array from the server
	for (port, value) in arrayconf['ports'].iteritems():
		#Setting up MUX port
		setPort(port)
		#Case output
		if value["type"]=="output":
			if value["value"]!=arrayprec.get(port, 0):
				pulse()
				print "pulse"
				arrayprec[port]=value["value"]
		#Case input
		if value["type"]=="input":
			arrayread[port]=read()
			temp = random.randint(0,255)
			arrayread[port]=temp

	#Converts the array in JSON
	arrayread={'sid': MID, 'ports': arrayread}
	arrayread=json.dumps(arrayread)
	#send array
	f=urllib2.urlopen( BASEURL + '/endpoint.php',arrayread)
	#read HTTP response
	arrayconf=f.read()
	print arrayconf
	#Converts JSON json from server in array
	arrayconf=json.loads(arrayconf)
	#One HTTP request every 5 seconds
	time.sleep(5)

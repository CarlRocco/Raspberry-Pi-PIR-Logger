import time
from datetime import datetime
import serial
import serial.tools.list_ports
import threading
import PIR_Data

##ilsdata = PIR_Data.ILSData()
ddm = 0
sdm = 0
rfLevel = 0
mod90 = 0
mod150 = 0
ddmlimit = .100
sdmlimit = 20
rflimit = -70
alarm = 0
running = False
ser = serial.Serial()


def initPort():
    comlist = serial.tools.list_ports.comports()
    connected = []
    for element in comlist:
        connected.append(element.device)
    print(str(connected))
    ser.port = '/dev/ttyACM0'
    #ser.port = '/dev/ttyUSB0'
    ser.baudrate = 9600
    ser.timeout = .8
    
def start():
    global running
    running = True
    if ser.isOpen() == False:
        ser.open()
    if serThread.isAlive() == False:
        serThread.start()
def stop():
    global running
    running = False
    ser.close()
    
def __calcRFLevel():
    hiByte = dataBytes[11]
    loByte = dataBytes[10]
    value = ((hiByte << 8) | loByte)
    if value > 65536/2:
        value = value - 65536
    return value/10
def __calc90Mod():
    hiByte = dataBytes[17]
    loByte = dataBytes[16]
    value = ((hiByte << 8) | loByte)
    return value/100
def __calc150Mod():
    hiByte = dataBytes[19]
    loByte = dataBytes[18]
    value = ((hiByte << 8) | loByte)
    return value/100
def __calcDDM():
    hiByte = dataBytes[23]
    loByte = dataBytes[22]
    value = ((hiByte << 8) | loByte)
    if value > 65536/2:
        value = value - 65536
    return value/10000
def __calcSDM():
    hiByte = dataBytes[21]
    loByte = dataBytes[20]
    value = ((hiByte << 8) | loByte)
    return value/100

##def getData():
##    return ilsdata
def getDDM():
    return ddm
def getSDM():
    return sdm
def getRFLevel():
    return rfLevel
def get90Mod():
    return mod90
def get150Mod():
    return mod150

#create an array of 58 elements (0 to 57) with all values = to 0
dataBytes = [0 for i in range(58)]

def readSerLoop():
    while True:
        if running:
            getSerial()

def getSerial():
    startFound = False
    while(startFound == False and running == True):
        try:
            inByte = ord(ser.read())
            pushList(inByte)
            if dataBytes[0]==90 and dataBytes[1]==195 and dataBytes[2]==56:               
                startFound = True
                global ddm
                global sdm
                global mod90
                global mod150
                global rfLevel
                global alarm
                mod90 = __calc90Mod()
                mod150 = __calc150Mod()
                rfLevel = __calcRFLevel()
                ddm = __calcDDM()
                sdm = __calcSDM()
                ser.flushInput()
                # test for out of tolerance
                if ddm > ddmlimit or ddm < -ddmlimit or rfLevel < rflimit:
                    #print("alarm = true")
                    alarm = 1
                else:
                    #print("alarm = false")
                    alarm = 0
        except:
            ddm = 999
            sdm = 999
            rfLevel = 999
                          
def pushList(Value):
    count = 0
    length = len(dataBytes)-1
    for element in range(length):
        dataBytes[count] = dataBytes[count+1]
        count += 1
    dataBytes[length] = Value
    
serThread = threading.Thread(target=readSerLoop)

import PIR_Com
import sqLiteData
import threading
import time
from datetime import datetime

running = False
#Serial Comm
PIR_Com.initPort()

def outputData():#NEED TO MAKE THIS AN ARRAY OF DATA FROM PIR_Com
    sqLiteData.insertILSData(PIR_Com.getDDM(), PIR_Com.getSDM(),
        PIR_Com.getRFLevel(), PIR_Com.get90Mod(), PIR_Com.get150Mod(), PIR_Com.alarm)

def startComm():
    PIR_Com.start()
    print("start")
startComm()

def stopComm():
    PIR_Com.stop()
    sqLiteData.disconnect()
    print("stop")

def setDDMAlarm(val):
    PIR_Com.ddmlimit = val
def setRFAlarm(val):
    PIR_Com.rflimit = val
def workerThread():
    print(sqLiteData.connect())
    #data output timing
    outputSecs = 1 #how often to send data (integer seconds)
    loopSpeed = .5 #how often to check status (seconds)
    updateCount = 0 #increments with each window update
    while True:
        setupdata = sqLiteData.getSetupData()
        print(setupdata)
        outputSecs = setupdata[0][2]
        if(setupdata[0][0] == 1):           
            setDDMAlarm(setupdata[0][4])
            setRFAlarm(setupdata[0][3])
            updateCount += 1
            #print(updateCount)
            if outputSecs == (loopSpeed*updateCount):
                #print("data out")
                outputData()
                updateCount = 0
        time.sleep(loopSpeed)
        
workThread = threading.Thread(target=workerThread)
workThread.start()

##while True:
##    test = input("What is your name?")
##    print("Hi " + test)
        

import sqlite3
from sqlite3 import Error
from datetime import datetime


ID = 0
# Setup Params
running = False
receivingData = False
ils_on = True
alarm = True
logInterval = 1
lowRF = -70.0
ddmLimit = .050
sdmLimit = 20.0

setupData = [0 for i in range(6)]



def connect():
    try:
        global conn
        conn = sqlite3.connect("/var/www/html/PIRLog.db")
        return "Connected Sucsessfully"
    except Error as e:
        print(e)
        print("fuck")
        return "Not Connected"

def disconnect():
    global conn
    conn.close()
    print("disconnected")

def createILSTable():
    global conn
    conn.execute('''CREATE TABLE IF NOT EXISTS ILSData(
         ID             INT PRIMARY KEY     NOT NULL,
         DATETIME       TEXT                NOT NULL,
         DDM            REAL,
         SDM            REAL,
         RF             REAL,
         MOD90          REAL,
         MOD150         REAL,
         ALARM          INT);''')
    
def createSettingsTable():
    global conn
    conn.execute('''CREATE TABLE IF NOT EXISTS Settings(
         running        INT,
         receivingData  INT,
         logInterval    INT,
         lowRF          REAL,
         ddmLimit       REAL,
         sdmLimit       REAL);''')
    cur = conn.cursor()
    data = [(1,1,1,-30.0,.050,20.0)]
    cur.executemany('INSERT INTO Settings VALUES(?,?,?,?,?,?)', data);
    conn.commit()

#NEED TO MAKE THE ARG AN ARRAY OF DATA INSTEAD
def insertILSData(ddm, sdm, rf, m90, m150, alarm):
    global conn
    global ID
    if ddm != 999:
        try:
            ID = getLastID() + 1
        except:
            ID = 0
        cur = conn.cursor()
        dt = datetime.now().strftime("%Y-%m-%d %H:%M:%S")
        data = [(ID,dt,ddm,sdm,rf,m90,m150,alarm)]
        cur.executemany('INSERT INTO ILSData VALUES(?,?,?,?,?,?,?,?)', data);
        conn.commit()

def getLastID():
    global conn
    cursor = conn.cursor()
    cursor.execute('''SELECT ID FROM ILSData ORDER BY ID DESC LIMIT 1''')
    row = cursor.fetchall()
    return row[0][0]

def read_from_db():
    try: 
        global conn
        cursor = conn.cursor()
        cursor.execute("SELECT * FROM ILSData")
        #cursor.execute("SELECT * FROM Settings")
        rows = cursor.fetchall()
        for row in rows:
            print(row)
    except:
        print("error reading database")
def getSetupData():
    try: 
        global conn
        cursor = conn.cursor()
        cursor.execute("SELECT * FROM Settings")
        rows = cursor.fetchall()
        return rows
    except:
        print("error reading database")
        
def dropILSTable():
    global conn
    cursor = conn.cursor()
    cursor.execute('''DROP TABLE ILSData''')
    conn.commit()
    ID = 0
def dropSettingsTable():
    global conn
    cur = conn.cursor()
    cur.execute('''DROP TABLE Settings''')
    conn.commit()
    
##connect()
##conn.execute("VACUUM")
##createILSTable()
##createSettingsTable()
##insertILSData()
##dropILSTable()
##read_from_db()
##dropSettingsTable()
##disconnect()
##"YYYY-MM-DD HH:MM:SS.SSS"

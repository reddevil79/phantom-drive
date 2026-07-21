# -*- coding: utf-8 -*-
import sys
import json

import codecs
sys.path.append("zk")

from zk import ZK, const
from zk.finger import Finger

conn = None
zk = ZK('192.168.1.201', port=4370, timeout=5, password=0, force_udp=False, ommit_ping=False)
uuid =  int(sys.argv[1])
fid =  int(sys.argv[2])
valid =  int(sys.argv[3])
biotemp = str(sys.argv[4])
try:
    # print ('Connecting to device ...')
    conn = zk.connect()
    # print ('Disabling device ...')
    conn.disable_device()
    fing = Finger(uuid,fid,valid,codecs.decode(biotemp, "hex"))

    template = conn.save_user_template(user=uuid, fingers=fing)

    
    print(template)
    # print ("Voice Test ...")
    conn.test_voice()
    # print ('Enabling device ...')
    # conn.enable_device()
except Exception as e:
    print ("Process terminate : {}".format(e))
finally:
    if conn:
        conn.disconnect()

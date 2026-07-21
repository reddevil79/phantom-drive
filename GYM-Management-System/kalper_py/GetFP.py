# -*- coding: utf-8 -*-
import sys
import json

from struct import pack #, unpack

import codecs
sys.path.append("zk")

from zk import ZK, const
from zk.finger import Finger

conn = None
zk = ZK('192.168.1.201', port=4370, timeout=5, password=0, force_udp=False, ommit_ping=False)
uuid =  int(sys.argv[1])
fing =  int(sys.argv[2])
try:
    # print ('Connecting to device ...')
    conn = zk.connect()
    # print ('Disabling device ...')
    conn.disable_device()
    template = conn.get_user_template(uid=uuid,temp_id=fing)
    # print(template.json_pack())
    print ('{"size":"%s", "uid":"%s", "fid":"%s", "valid":"%s", "template":"%s" }' % (template.size, template.uid, template.fid, template.valid, template.mark ))
    # print ("UID      : %s" % template.uid)
    # print ("FID      : %s"% template.fid)
    # print ("Valid    : %s" % template.valid)
    # print ("Template : %s" % template.json_pack())
    # print ("Mark     : %s" % template.mark)

    # print ("Voice Test ...")
    # conn.test_voice()
    # print ('Enabling device ...')
    conn.enable_device()
except Exception as e:
    print ("Process terminate : {}".format(e))
finally:
    if conn:
        conn.disconnect()

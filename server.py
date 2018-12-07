# -*- coding: utf-8 -*-

import urllib
import httplib

TIMEOUT = 3
urllib.socket.setdefaulttimeout(TIMEOUT)

udpaddr = '239.0.0.63:1234'
try:
	connectp = urllib.urlopen('http://proxytv.ru/iptv/php/onechan.php?ip=%s' % udpaddr)
except:
	print u'Сервер не отвечает'
else:
	udpxyaddr = connectp.read()
	try:
		connectu = httplib.HTTPConnection(udpxyaddr)
	except:
		print 'UDPXY дохлый'
	else:
		connectu.request('GET', '/status')
		gresponse = connectu.getresponse()
		if gresponse.status == 200:
			namefile = 'plist.m3u'
			namechann = 'Футбол 1'
			extm3u = '#EXTM3U\n'
			extinf =  '#EXTINF:-1,%s\n' % namechann
			stream = 'http://%s/udp/%s\n' % (udpxyaddr, udpaddr)
			m3ufile = open(namefile, 'w')
			m3ufile.write(extm3u)
			m3ufile.write(extinf)
			m3ufile.write(stream)
			m3ufile.close()
			print u'Плейлист создан успешно'
		if gresponse.status != 200:
			print u'Это не UDPXY'

import urllib
import httplib

TIMEOUT = 3
urllib.socket.setdefaulttimeout(TIMEOUT)

udpaddr = '239.0.0.182:1234'
try:
	connectp = urllib.urlopen('http://proxytv.ru/iptv/php/onechan.php?ip=%s' % udpaddr)
except:
	print u'Сервер не отвечает'
else:
	udpxyaddr = connectp.read()
	try:
		connectu = httplib.HTTPConnection(udpxyaddr)
	except:
		print 'UDPXY дохлый'
	else:
		connectu.request('GET', '/status')
		gresponse = connectu.getresponse()
		if gresponse.status == 200:
			namefile = 'plist.m3u'
			namechann = 'Футбол 2'
			extm3u = '#EXTM3U\n'
			extinf =  '#EXTINF:-1,%s\n' % namechann
			stream = 'http://%s/udp/%s\n' % (udpxyaddr, udpaddr)
			m3ufile = open(namefile, 'w')
			m3ufile.write(extm3u)
			m3ufile.write(extinf)
			m3ufile.write(stream)
			m3ufile.close()
			print u'Плейлист создан успешно'
		if gresponse.status != 200:
			print u'Это не UDPXY'






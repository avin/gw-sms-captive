#!/bin/sh

set -e -u

HUB=$1 # target host's IP address

/usr/sbin/conntrack -D conntrack --orig-src $HUB
#!/bin/sh

set -e -u

HUB=$1 # target host's IP address

value()
{
    echo ${1#*=}
}

/usr/sbin/conntrack -L conntrack -s $HUB |
    while read proto _ _ src dst sport dport _; do
       /usr/sbin/conntrack -D conntrack \
          --proto `value $proto` \
          --orig-src `value $src` \
          --orig-dst `value $dst` \
          --sport `value $sport` \
          --dport `value $dport`
done
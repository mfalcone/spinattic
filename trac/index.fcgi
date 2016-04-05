#!/bin/bash
export TRAC_ENV="/home/spinattic/dev.spinattic.com/trac"

# If you get 500 errors maybe you need to set/export the LD_LIBRARY_PATH,
# just uncomment the following lines and make sure it points to the same place
# you used before (Environment Setup).
# export LD_LIBRARY_PATH=/home/{USER}/lib/
# export PATH="/home/{USER}/bin:$PATH"

exec /home/spinattic/dev_spinattic_com_trac_trac/cgi-bin/trac.fcgi

# unless you're using regular CGI in which case comment the above
# and uncomment the next line
# exec /home/{USER}/share/trac/cgi-bin/trac.cgi


def multicore():
    
    import os
    import subprocess
    
    # The os.setsid() is passed in the argument preexec_fn so
    # it's run after the fork() and before  exec() to run the shell.
    pro = subprocess.Popen('pyomo_ns >& ns.out &', stdout=subprocess.PIPE, 
                           shell=True, preexec_fn=os.setsid) 
    
    return pro

#    import os
#    
#    os.startfile
#    
#    os.system('pyomo_ns >& ns.out &')
#    os.system('dispatch_srvr >& dispatch_srvr.out &')
#    os.system('pyro_mip_server >& pyro_mip_server1.out &')
#    os.system('pyro_mip_server >& pyro_mip_server2.out &')
    
#    from subprocess import call
#    call(["pyomo_ns >& ns.out &"])
#    call('dispatch_srvr >& dispatch_srvr.out &')
#    call('pyro_mip_server >& pyro_mip_server1.out &')
#    call('pyro_mip_server >& pyro_mip_server2.out &')


def cutpllproces(pro):
    
    import os
    import signal

    os.killpg(os.getpgid(pro.pid), signal.SIGTERM)
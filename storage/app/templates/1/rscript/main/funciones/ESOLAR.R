#Calculo de la energia solar fotovoltaica  producida
EREAL<- list()
EPRONOSTICO<- list()
Wp=375*10^6 # capacidad instalada#9MWp para bogota 
mf=0.85 # factor de desajuste
e=0.64  #eficiencia conbinada--- baterias (0.8),controlador(0.9), inversor(0.9)

for (j in 1:maxP) {
  if (j==1) {
    
    EREAL[[j]]<- wComp24[,8]*mf*Wp*e/1e9  # root mean squared error#
    EPRONOSTICO[[j]]<- MVarts[[j]][,8]*mf*Wp*e/1e9  # root mean squared error#
    
  }else{
    
    EREAL[[j]]<-wComp24[,8]*mf*Wp*e/1e9
    EPRONOSTICO[[j]]<-MVarts[[j]][,8]*mf*Wp*e/1e9
  }
}

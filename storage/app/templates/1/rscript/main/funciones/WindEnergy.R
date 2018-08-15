#' Function that calculate the wind energy from a distribution.
#' It couldbe a weibull distribution or a histogram of frecuencies
WindEnergy <- function(Velocidad,yearsPron,weibullSI,delta=1) {
  if (weibullSI==1) {
    WDistrib <- WeibullAB(Velocidad,5,mean(Velocidad))
    Distrib <- dweibull(seq(0,max(Velocidad)*1.2,delta),shape = WDistrib[[1]],scale = WDistrib[[2]])
    POT <- as.double(rbind(Vestas117(seq(0,max(Velocidad)*1.2,delta))))
    Energy <- Distrib*POT*8760*yearsPron*delta/1000000 #GW
  }else {
    if (min(Velocidad)>0) {
    mintemp <- 0  
    }else {
      mintemp <- min(Velocidad)
    }
    Distrib <- hist(x = Velocidad,breaks = seq(mintemp,max(Velocidad)*1.2,delta))
    POT <- as.double(rbind(Vestas117(Distrib$mids)))
    Energy <- Distrib$density*8760*yearsPron*POT*delta/1000000#GW
  }
  return(Energy)
}
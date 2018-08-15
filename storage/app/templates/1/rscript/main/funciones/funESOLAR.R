#'Funcion que calcula la energia solar en MWh
#'@param Serie: Valores de irradiancia para diferentes rezagos
#'@param Wp: capacidad instalada
#'@return E: Vector con energía solar
Esolar <- function(Serie,Wp) {
  E<- list()
  mf=0.85 # factor de desajuste
  e=0.64  #eficiencia conbinada--- baterias (0.8),controlador(0.9), inversor(0.9)
  
  E<- sum(Serie*0.96*Wp*0.9*30)/1e3

  
  return(E)
}
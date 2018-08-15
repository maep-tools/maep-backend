u<- function(alpha,beta,V1) {
  #Función 1 método Maxima Verosimilitud
  N <- length(V1)
  u <- sum(V1**alpha/beta**alpha)-N
#u=sum((V1/beta)**alpha)-N
return(u)
}
v <- function(alpha,beta,V1) {
  #Función 2 método Maxima Verosimilitud
  N <- length(V1)
  v <- alpha*sum(log(V1)*(((V1**alpha)/(beta**alpha))-1))-N
  #v=(alpha*sum(log(V1)*(((V1/beta)**alpha)-1)))-N
  return(v)
}
upa<-function(alpha,beta,V1){
  #Derivada función 1 respecto a alpha
  N <- length(V1)
  upa <- sum(((V1/beta)**alpha)*log(V1/beta))
  return(upa)
}
upb<-function(alpha,beta,V1){
  #Derivada función 1 respecto a beta
  N <- length(V1)
  upb <- (-1*(alpha/beta)*sum((V1/beta)**alpha))
  return(upb)
}

vpa<-function(alpha,beta,V1){
  #Derivada función 2 respecto a alpha
  N <- length(V1)
  vpa <- sum(log(V1)*(((V1/beta)**alpha)-1))+alpha*sum(log(V1)*(log(V1/beta)*(V1/beta)**alpha))
  return(vpa)
}

vpb<-function(alpha,beta,V1){
  #Derivada función 2 respecto a beta
  N <- length(V1)
  vpb <- -(alpha**2)*sum(log(V1)*(V1**alpha)*(beta**(-alpha-1)))
  return(vpb)
}

WeibullAB<-function(V1,alpha1,beta1){
#Función que determina los parámetros de Weibull para una serie de datos dada
#-V1 una lista que contiene los valores de velocidad
  #-alpha1 valor inicial del factor de forma una sugerencia es un valor entre 1 y 10
  #-beta1 valor inicial del factor de escala valor inicial sugerido es la media de V1

  #V1=np.asarray(V1)
  alpha <- alpha1
  beta <- beta1
  contador <- 0
  alphan <- 5
  betan <- 10
  for (i in 1:150) {
    alphan <- alpha-((u(alpha,beta,V1)*vpb(alpha,beta,V1)-v(alpha,beta,V1)*upb(alpha,beta,V1))/(upa(alpha,beta,V1)*vpb(alpha,beta,V1)-upb(alpha,beta,V1)*vpa(alpha,beta,V1)))
    betan <- beta-((v(alpha,beta,V1)*upa(alpha,beta,V1)-u(alpha,beta,V1)*vpa(alpha,beta,V1))/(upa(alpha,beta,V1)*vpb(alpha,beta,V1)-upb(alpha,beta,V1)*vpa(alpha,beta,V1)))
    contador <- contador+1
    if ((abs(alpha-alphan)/alpha<0.005) & (abs(beta-betan)/beta<0.005)) {
      print("valor encontrado")
      return (c(alpha,beta))
      
    }
    alpha <- alphan
    beta <- betan
  }
  
}


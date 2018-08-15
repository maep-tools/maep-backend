criterios_fisicos_VARi <- function(info_name,ts_seriesFull,VAR8,cap_inst) {
  #############################################################################################################
  # Evaluaci?n de medias 
  #------------------------------------------------------------------------------------------------------------
  # Calculo de la media por afluente, serie e?lica o solar. Si la media sobrepasa el limite inferior o superior se 
  # almacenar? 1 en un vector de evaluaci?n de criterios con 9 posiciones x n series. 
  # En este caso se usar? VAR8 para comparar 
  #------------------------------------------------------------------------------------------------------------
  #cargar las funciones requeridas
  source('./funciones/limites_f.R')
  limites_fisicos <- limites_f(info_name,ts_seriesFull)
  VAR8 = VAR8
  
  # medias
  ts_VAR_medias = array(dim=c(length(info_name),1))
  for (i in 1:length(info_name)) {
    ts_VAR_medias[i] = mean(VAR8[,i])
  }
  
  # desv. Est
  ts_VAR_desvE = array(dim=c(length(info_name),1))
  for (i in 1:length(info_name)) {
    ts_VAR_desvE[i] = sd(VAR8[,i])
  }
  
  # Mediana  
  ts_VAR_mediana= array(dim=c(length(info_name),1))
  for (i in 1:length(info_name)) {
    ts_VAR_mediana[i] = median(VAR8[,i])
  }
  
  # Coeficiente de variaci?n 
  ts_VAR_CV = ts_VAR_desvE/ts_VAR_medias
  
  # mean. dev
  ts_VAR_meanD = array(dim=c(length(info_name),1))
  for (i in 1:length(info_name)) {
    ts_VAR_meanD[i] = mad(VAR8[,i], median(VAR8[,i]))
  }
  
  # Percentil 97.5%
  ts_VAR_qtl975 = array(dim=c(length(info_name),1))
  for (i in 1:length(info_name)) {
    ts_VAR_qtl975[i] = quantile(VAR8[,i],0.975)
  }
  
  # Percentil 2.5%
  ts_VAR_qtl25 = array(dim=c(length(info_name),1))
  for (i in 1:length(info_name)) {
    ts_VAR_qtl25[i] = quantile(VAR8[,i],0.025)
  }
  
  # M?ximo valor
  ts_VAR_max= array(dim=c(length(info_name),1))
  for (i in 1:length(info_name)) {
    ts_VAR_max[i] = max(VAR8[,i])
  }
  
  # M?nimo valor
  ts_VAR_min= array(dim=c(length(info_name),1))
  for (i in 1:length(info_name)) {
    ts_VAR_min[i] = min(VAR8[,i])
  }
  
  #crear list con parametros del pronostico
  param_pron <-list()
  param_pron[[1]] <-ts_VAR_medias
  param_pron[[2]] <-ts_VAR_desvE
  param_pron[[3]] <-ts_VAR_mediana
  param_pron[[4]] <-ts_VAR_CV
  param_pron[[5]] <-ts_VAR_meanD
  param_pron[[6]] <-ts_VAR_qtl975
  param_pron[[7]] <-ts_VAR_qtl25
  param_pron[[8]] <-ts_VAR_max
  param_pron[[9]] <-ts_VAR_min
  
  #__________________________________________________________________
  # Ejecutar prueba sobre variables
  
  #Vector de evaluaci?n 
  vec_eval_crit = array(dim=c(9,length(info_name)))
  
  for (i in 1:length(info_name)) {
    #medias
    param = 1
    if (param_pron[[param]][i] > limites_fisicos[[((param*2)-1)]][i]| param_pron[[param]][i] <limites_fisicos[[(param*2)]][i]) {
      vec_eval_crit[param,i] = 1
    } else {
      vec_eval_crit[param,i] = 0
    }
    param =param+1
    #Desv. Estandar
    if (param_pron[[param]][i] > limites_fisicos[[((param*2)-1)]][i]| param_pron[[param]][i] <limites_fisicos[[(param*2)]][i]){
      vec_eval_crit[param,i] = 1
    } else {
      vec_eval_crit[param,i] = 0
    }
    param =param+1
    # mediana
    if (param_pron[[param]][i] > limites_fisicos[[((param*2)-1)]][i]| param_pron[[param]][i] <limites_fisicos[[(param*2)]][i]) {
      vec_eval_crit[param,i] = 1
    } else {
      vec_eval_crit[param,i] = 0
    }
    param=param+1
    #Coeficiente de variacion 
    if (param_pron[[param]][i] > limites_fisicos[[((param*2)-1)]][i]| param_pron[[param]][i] <limites_fisicos[[(param*2)]][i]) {
      vec_eval_crit[param,i] = 1
    } else {
      vec_eval_crit[param,i] = 0
    }
    param = param+1
    # Mean. Dev
    if (param_pron[[param]][i] > limites_fisicos[[((param*2)-1)]][i]| param_pron[[param]][i] <limites_fisicos[[(param*2)]][i]) {
      vec_eval_crit[param,i] = 1
    } else {
      vec_eval_crit[param,i] = 0
    }
    param = param+1
    # percentil 97.5
    if (param_pron[[param]][i] > limites_fisicos[[((param*2)-1)]][i]| param_pron[[param]][i] <limites_fisicos[[(param*2)]][i]) {
      vec_eval_crit[6,i] = 1
    } else {
      vec_eval_crit[6,i] = 0
    }
    param=param+1
    # percentil 2.5
    if (param_pron[[param]][i] > limites_fisicos[[((param*2)-1)]][i]| param_pron[[param]][i] <limites_fisicos[[(param*2)]][i]){
      vec_eval_crit[7,i] = 1
    } else {
      vec_eval_crit[7,i] = 0
    }
    param =param+1
    # valor m?ximo
    if (param_pron[[param]][i] > limites_fisicos[[((param*2)-1)]][i]| param_pron[[param]][i] <limites_fisicos[[(param*2)]][i]) {
      vec_eval_crit[8,i] = 1
    } else {
      vec_eval_crit[8,i] = 0
    }
    param=param+1
    # valor m?ximo
    if (param_pron[[param]][i] > limites_fisicos[[((param*2)-1)]][i]| param_pron[[param]][i] <limites_fisicos[[(param*2)]][i]) {
      vec_eval_crit[9,i] = 1
    } else {
      vec_eval_crit[9,i] = 0
    }
    
  }
  # Pi esta definido cómo t/n, Donde t corresponde a la cantidad de series que cumplen todos los parametros, add up 0.
  # y n es la cantidad total de plantas/series que se incluyen.
  n = length(info_name)
  sum_pi = array(dim=c(1,length(info_name)))
  sum_pi = colSums(vec_eval_crit)
  t=sum(sum_pi == 0)
  Pi = t/n
  
  #Qi calific a la calida de la series teniendo en cuenta el tamaño de la planta. Esta definido como la sumatoria
  #de la potencia de las plantas que cumplen con todos los criterios (add up to 0) sobre la sumatoria total de la capacidad
  #instalada de plantas analizadas.
  ind = which(sum_pi == 0)
  cap_t = cap_inst[ind]
  sum_cap_t =sum(cap_t)
  sum_cap_total = sum(cap_inst)
  Qi = sum_cap_t/sum_cap_total
  
  indices_pi_qi = array(dim=c(1,2))
  indices_pi_qi[,1] =Pi
  indices_pi_qi[,2] =Qi
  
  list_criterios_fisicos =list()
  list_criterios_fisicos[[1]] = vec_eval_crit
  list_criterios_fisicos[[2]] = indices_pi_qi
 
  return(list_criterios_fisicos)
}





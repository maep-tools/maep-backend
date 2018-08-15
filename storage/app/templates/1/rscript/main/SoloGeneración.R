graphics.off()
library(fpp)
library(gdata)
require(tseries)
require(MTS)
require(graphics)
require(Metrics)
require(forecast)
require(corrplot)
##############################################################################################################
# CARGAR FUNCIONES DEL CODIGO
source('./funciones/data_load.R')
source('./funciones/adf_test.R')
source('./funciones/VAR_select_maxP.R')
source('./funciones/error_VARpron.R')
source('./funciones/limites_f.R')
source('./funciones/criterios_fisicos_VARi.R')
source('./funciones/energy_y_H.R')
source('./funciones/correl_function.R')
source('./funciones/correccion_series.R')
source('./funciones/Normalizar.R')
source('./funciones/DESNormalizar.R')
source('./funciones/m1.R')
source('./funciones/m2.R')
source('./funciones/m3.R')
source('./funciones/Weibull.R')
source('./funciones/beta.R')
source('./funciones/PotWind.R')
source('./funciones/WindEnergy.R')
source("./funciones/funESOLAR.R")
source("./funciones/Synte2.R")
source("./funciones/WhiteN.R")
###############################################################################################################
# IMPORTAR Y AJUSTAR DATOS. 
#--------------------------------------------------------------------------------------------------------------
#Los datos a cargar deben ser incluidos en la carpeta "series meses CSV" en formato "*.csv", siguiendo el 
#formato de los archivos iniciales.  Adicionalmente debe incluirse su nombre en el archivo "info_hidro_var.csv".
#El c?digo lee el archivo "info_hidro_var.csv" toma los nombres de los archivos y los busca en la carpeta 
#"series meses CSV"
#--------------------------------------------------------------------------------------------------------------

# Cargar datos
setwd(".") #Cambia el workspace
datafolder = paste('series hidricas sistema completo', sep='/')
#datafolder=paste('series hidricas sistema completo',sep = '/')

#datafolder = paste('series hidricas sistema completo', sep='/') #Analisis con mayor cantidad de series 
info_series ='info_var_auct.csv'

# Funcion para extaer datos, entrega un list con 3 posiciones, 1. Matriz de informaci?n en formato "double", 
# 2.vector de fechas de todos las series utilizadas y 3. matriz de nombres, codigos, obtenida de 'info_hidro_var.csv'
resultado_list<- data_load(datafolder,info_series)
seleccion_list = correccion_series(resultado_list)


# 1. Extraer series
m_series <- seleccion_list[[1]]
m_series_copy = m_series

normalizar=0 #0

if (normalizar == 1) {
  salida_norm = Normalizador(m_series)
  m_series = salida_norm[[1]]
  maximos = salida_norm[[2]]
}

fechas <- seleccion_list[[7]][[1]] #Extrae la informaci?n de fechas
monts_data <-seleccion_list[[7]][[2]] # Cantidad de meses en el horizonte temporal historico
m_dates <- seleccion_list[[7]][[3]] # crea una matriz de fechas para cada serie 

# 3. Extrate informaci?n de las series
dataN <- resultado_list[[3]]
info_name <- seleccion_list[[2]]
namesS <- seleccion_list[[3]]
FP <- seleccion_list[[4]]#Factor de produccion
tipo_recurso <- seleccion_list[[5]]
cap_inst = seleccion_list[[6]]

##############################################################################################################
# SERIES DE TIEMPO

# Definicion serie de tiempo "ts_seriesFull" incluye los periodos de entrenamiento y comparacion
# Obtiene el a?o y mes inicial a partir de la informaci?n extaida en la funci?n load_data
month_i =as.numeric(format(m_dates[[1,1]], "%m"))
year_i =as.numeric(format(m_dates[[1,1]], "%Y"))
# Obtiene el a?o y mes final a partir de la informaci?n extaida en la funci?n load_data
month_f = as.numeric(format(m_dates[[length(m_dates[,1]),1]], "%m"))
year_f = as.numeric(format(m_dates[[length(m_dates[,1]),1]], "%Y"))

ts_seriesFull<- ts(m_series,start=c(year_i,month_i),end=c(year_f,month_f),frequency = 12,c("mts","ts","matrix"),names = info_name)

# Serie de tiempo de entrenamiento (2005 a 2012), 96 datos que corresponden a 8 a?os 
year_it =year_i
month_it = month_i
ts_seriesFull <- window(ts_seriesFull,c(year_it,month_it),c(year_f,month_f),12)
# El usuario debe seleccionar los a?os de entrenamiento, en este caso el a?o final de entrenamiento es 2012
year_ft = 2015
month_ft = 12

ts_series<-window(ts_seriesFull,c(year_it,month_it),c(year_ft,month_ft))

# Figuras de todas las variables vs viento, visualizaci?n complementariedad
len = length(info_name)
indcV = which(tipo_recurso==2)
indcS = which(tipo_recurso==3)

var_seleccion_automatica = vars::VARselect(ts_series, lag.max = 12, type = c("const"), season =12, exogen = NULL)
#c("const", "trend", "both", "none")

maxP = as.numeric(var_seleccion_automatica$selection[1])
maxP = 4
# Inicializa los arreglos por llenar
VARts_series = list()
myAIC = array(dim=c(maxP,1))
myBIC = array(dim=c(maxP,1))
myHQ = array(dim=c(maxP,1))
VARp_resd = list()

for(j in 1:maxP){ 
  VARts_series[[j]] = vars::VAR(ts_series,p=j,season=12)
  myAIC[j,] = var_seleccion_automatica$criteria[,j][1]
  #myBIC[j,] = BIC(VARts_series[[j]])
  #myHQ[j,] = VARts_series[[j]]$hq
  VARp_resd[[j]]= residuals(object = VARts_series[[j]]) #Extrae los residuales por VAR(p)
}


yearsPron =5# Cambiar cuando se quiera aumentar o disminuir la cantidad de a?os
maxP=4
VAR_series_pred = list()
for (j in 1:maxP) {
  VAR_series_pred[[j]]<-forecast(VARts_series[[j]], h = (yearsPron*12))
}

# Definicion ts real para comparacion
# 1. Determinar que anos fueron pronosticados
year_ii = year_ft+1
month_ii = 1
year_ff = year_ii+yearsPron-1 # Cambia con la cantidad de anos "yearsPron"
month_ff = 12

# 2. Establecer ts del periodo pronosticado 
len=length(info_name)
endRow=length(m_series[,1])
startRow = endRow-((yearsPron*12)-1)
m_comp=m_series[startRow:endRow,1:len] # toma los ultimos valores del vector m_series 





# 5. Seleccion la informacion real de los anos de comparacion (2013-2015)
ts_comp <-window(ts_seriesFull,c(year_ii,month_ii),c(year_ff,month_ff))

#dev.off()
# 6. Grafias de comparacion por autorregresivo para todas las series 

#6.1. Graficas por resago todas las series
a =length(info_name)
b = 2
c = 2

for (i in 1:maxP) {
  #x11()
  par(mfrow=c(c,b))
  lgnd = paste("VAR(",i,")",sep = "")
  for (j in 1:length(ts_series_i)) {
    if(j==indcV){n_recurso="Velocidad"}else if(j==indcS){n_recurso="Irradiacion"}else{n_recurso="Caudal"}
    title =paste(n_recurso,"real vs ",n_recurso, "reconstruido para ",namesS[j])
    limite = max(ts_comp[,j])*1.5
    plot(ts_comp[,j],main=title, cex =0.9, ylab="Aportes (m^3/s)",ylim=c(0, limite),col="blue")
    lines(ts_series_i[[j]][,i],col="black",lty=2)
    legend("top",legend=c("Real",lgnd),lty=c(1,2), col=c("blue", "black"),horiz = TRUE)
  }
}






#AQUI Va La modificacion


# 3. Extraer la informaci?n por escenario
#informaci?n por rezago (1:10)
VARi = list()

for(i in 1:maxP){
  valores <- VAR_series_pred[[i]]$mean[1][[1]]
  for (j in 2:length(info_name)) {
    valores <- cbind(valores,VAR_series_pred[[i]]$mean[j][[1]])
  }
  #creaci?n de variables internamente 
  assign(paste("VAR", i, sep = ""), valores) 
  VARi[[i]]=valores
}

# 4.  Informacion por serie y en formato serie de tiempo 
serie_i=list()
ts_series_i=list()
cant_datos = yearsPron*12
matriz_prueba =array(dim=c(cant_datos,maxP))

for (j in 1:ncol(VARi[[1]])) {
  for (i in 1:length(VARi)) {
    matriz_prueba[,i]= VARi[[i]][,j]
  }
  serie_i[[j]]= matriz_prueba
  ts_series_i[[j]] = ts(matriz_prueba,c(year_ii,month_ii),c(year_ff,month_ff), frequency = 12,c("mts","ts","matrix"))
}

#GENERACION SINTETICA 
seleccionado <- 4
#SYnte2 es el archivo
cantsimu <- 10
SintecGen <- Sint(m_series_copy,VARi[[seleccionado]],cantsimu)
PromediosSimu <- list()
#desv
desviax <- list()
for (i in 1:ncol(m_series_copy)) {
  desviax[[i]] <- sd(m_series_copy[,i])
}

par(mfrow=c(1,1))
for (i in 1:length(info_name)) {
  
  liminfsint <- 0
  limsupsint <- max(SintecGen[[1]][,i])*1.5
  
  if (min(SintecGen[[1]][,i])>0) {
    liminfsint <- min(SintecGen[[1]][,i])-min(SintecGen[[1]][,i])*1.75
    
  }
  
    ytit="Velocidad de viento (m/s)"
  
  plot(ts(SintecGen[[1]][,i],c(year_ii,1),c(year_ff,12),12),lty=2,ylim = c(liminfsint,limsupsint),main=namesS[,i],ylab=ytit,xlab="Tiempo")
  for (j in 2:cantsimu) {
    lines(ts(SintecGen[[j]][,i],c(year_ii,1),c(year_ff,12),12),col=j+68,lty=2)
    
  }
  
  #lines(ts_comp[,i],lwd=3,col="red")
  lines(ts(VARi[[seleccionado]][,i]-1.96*desviax[[i]],c(year_ii,1),c(year_ff,12),12),lwd=3,col="blue")
  lines(ts(VARi[[seleccionado]][,i]+1.96*desviax[[i]],c(year_ii,1),c(year_ff,12),12),lwd=3,col="blue")
  
  mPrueba1=SintecGen[[1]][,i]
  for (j in 2:cantsimu) {
    mPrueba1=cbind(mPrueba1,SintecGen[[j]][,i])
    
  }
  PromediosSimu[[i]] <- mPrueba1
  prueba_avg1=rowMeans(mPrueba1)
  ts_pr_avg1 = ts(prueba_avg1,c(year_ii,1),c(year_ff,12),12)
  lines(ts_pr_avg1,col="green",lwd=5)
  lines(ts(VARi[[seleccionado]][,i],c(year_ii,1),c(year_ff,12),12),lwd=5,col="blue")
  
  
}



SeriesComparar <- list()
for (j in 1:cantsimu) {
  SeriesComparar[[j]] <- PromediosSimu[[1]][,j]
  for (i in 2:length(info_name)) {
    
    SeriesComparar[[j]] <- cbind(SeriesComparar[[j]],PromediosSimu[[i]][,j])
  }
}

sintepGen1 <- list()
for (i in 1:cantsimu){
  sintepGen1[[i]] <- criterios_fisicos_VARi(info_name = info_name,ts_seriesFull =ts_series,VAR8 = SeriesComparar[[i]],cap_inst = cap_inst)[[2]][,1]
}
sintepGen2 <- list()
for (i in 1:cantsimu){
  sintepGen2[[i]] <- criterios_fisicos_VARi(info_name = info_name,ts_seriesFull =ts_series,VAR8 = SeriesComparar[[i]],cap_inst = cap_inst)[[2]][,2]
}


exportarfiltrado <-PromediosSimu[[1]]
for (i in 2:length(info_name)){
  exportarfiltrado <- cbind(exportarfiltrado,PromediosSimu[[i]])
  
}
#PROMEDIO SIMU ES EL Q TIENE LA INFO
library(xlsx)
write.xlsx(exportarfiltrado, "C:/Users/ab.pedraza1391/SharePoint/Acuerdo 2 - Productos/Modelo subastas/series subastas/mydata.xlsx")

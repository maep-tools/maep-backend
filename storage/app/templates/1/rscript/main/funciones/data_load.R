data_load <- function(datafolder,info_series) {
#--------------------------------------------------------------------------------------------------------------
#Los datos a cargar deben ser incluidos en la carpeta "series meses CSV" en formato "*.csv", siguiendo el 
#formato de los archivos iniciales.  Adicionalmente debe incluirse su nombre en el archivo "info_hidro_var.csv".
  
#El código lee el archivo "info_hidro_var.csv" toma los nombres de los archivos y los busca en la carpeta 
#"series meses CSV"
#--------------------------------------------------------------------------------------------------------------
  
  # Cargar la información de nombres
  datafolderN = paste(datafolder,info_series, sep='/')
  dataN <- read.csv(datafolderN, header=T)
  dataN #Visualizar en la consola
  
  # Extraer nombre y codigo de referencia de la serie 
  info_name <- dataN[,1]
  namesS <-dataN[,5]
  # Ciclo para abrir archivos y crear matriz de información 
  for(n in 1:length(info_name)){
    if(n == 1)
    {
      filename <- paste(info_name[n],".csv",sep = "")# Cambiar el nombre del archivo que se quiere buscar
      filename <- paste(datafolder, filename, sep='/')
      data <- read.csv(filename, header=T)
      mytsa <-data$valor
      mytsD <-as.Date(data$fecha,format="%d/%m/%Y")
      m <- mytsa
      f <- mytsD
    }
    else { 
    len <-length(m)
    filename <- paste(info_name[n],".csv",sep = "") # Cambiar el nombre del archivo que se quiere buscar
    filename <- paste(datafolder, filename, sep='/')
    data <- read.csv(filename, header=T)
    mytsa <-data$valor
    mytsD <-as.Date(data$fecha,format ="%d/%m/%Y")
    m <- append(m,mytsa,after = len)
    f <- append(f,mytsD,after = len)
    }
  }
  
  # creación matriz de datos 
  rowM <-length(mytsa)
  colM <-length(m)/length(mytsa)
  m_series <- matrix(m,nrow = rowM,ncol = colM) #vec2mat, crea lamatris con la información de cada serie
  
  resultado <- c()
  resultado[[1]] <- m_series
  resultado[[2]] <- f
  resultado[[3]] <- dataN
  
  return(resultado)
}

library.path <- .libPaths(c("/home/taps/R/x86_64-pc-linux-gnu-library/3.6", .libPaths()))
library("openair", lib.loc = library.path)
data("mydata")
#summaryPlot(mydata, clip = TRUE, percentile = 0.80)
#summaryPlot(selectByDate(mydata,year = c(2000,2001)),type = "density")

png(filename = "/opt/lampp/htdocs/taps/rscript/summaryPlot.png" , width = 500,height = 500)
summaryPlot(selectByDate(mydata,year = c(2000),month=1),type = "density",
            avg.time = "hour",period="months",
            col.trend = "Black",
            col.data = "green")
dev.off()


# import data from csv
N <- read.csv("/opt/lampp/htdocs/taps/rscript/dataset1.csv" , sep = ";" , header = TRUE)
#print(N)
png(filename = "/opt/lampp/htdocs/taps/rscript/summaryPlotFromCsv.png" , width = 500,height = 500)
summaryPlot(N, clip = FALSE)
dev.off()

#N <- as.matrix(read.csv("dataset1.csv" , sep = ";" , header = TRUE), directed = TRUE)
#g <- graph.adjacency(N,mode="directed",weighted = TRUE)
#png(filename = "summaryPlot.png" , width = 500,height = 500)
#summaryPlot(N, clip = TRUE, percentile = 0.80)
#dev.off()


#scatterPlot
png(filename = "/opt/lampp/htdocs/taps/rscript/scatterPlot.png" , width = 500,height = 500)
scatterPlot(mydata, x="nox", y="no2", z="wd", type="season")
dev.off()
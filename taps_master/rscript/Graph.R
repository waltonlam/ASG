library.path <- .libPaths(c("/home/taps/R/x86_64-pc-linux-gnu-library/3.6", .libPaths()))
library("igraph", lib.loc = library.path) #importing the library
#we are expecting a matrix in the file matrix.csv
N <- as.matrix(read.csv("/opt/lampp/htdocs/taps/rscript/matrix.csv" , sep = ";" , header = FALSE), directed = TRUE)
g <- graph.adjacency(N,mode="directed",weighted = TRUE)
png(filename = "/opt/lampp/htdocs/taps/rscript/graph.png" , width = 500,height = 500)
plot(g,g.vertex.color="lightblue")
dev.off()

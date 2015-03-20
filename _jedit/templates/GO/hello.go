/*
compilazione:
scopo del programma:
*/

package main

import "fmt"

type Inventory struct {
    Material string
    Count    uint
}
var sweaters Inventory;

func init(){
    sweaters = Inventory{"wool", 17}
}

func main() {
    fmt.Println("Hello, go")
}



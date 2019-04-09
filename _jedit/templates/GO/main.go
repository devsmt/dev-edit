/*
compilazione:
    go build 01.go
*/
/*
scopo del programma:

*/

package main

import (
    "fmt"
    "os"
    "strings"
)


//-------------------------------------------------------
// constants
//-------------------------------------------------------
const S = "test str"
//-------------------------------------------------------
// types
//-------------------------------------------------------
// struct type
type Vertex struct {
    X int
    Y int
}

type Inventory struct {
    Material string
    Count    uint
}
//-------------------------------------------------------
// global vars
//-------------------------------------------------------
var i int = 0
var sweaters Inventory;

var a_str = []string{
   "aaaa",
   "bbbb",
}

//-------------------------------------------------------
// functions
//-------------------------------------------------------
func _add(x int, y int) int {
    return x + y
}

func _swap(x, y string) (string, string) {
    // multi return
    return y, x
}

//-------------------------------------------------------
// main
//-------------------------------------------------------
func init(){
    sweaters = Inventory{"wool", 17}
}
func main() {
    sum := 0
    fmt.Println("Happy", math.Pi, "Day")
    fmt.Printf("Now you have %d problems. \n", x)
    if len(os.Args) > 1 {
        who = strings.Join(os.Args[1:], " ")
    }
    return
}









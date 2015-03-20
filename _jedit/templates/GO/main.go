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
    "strings"
)

//-------------------------------------------------------
// global vars
//-------------------------------------------------------
var i int = 0


//-------------------------------------------------------
// constants
//-------------------------------------------------------
const S = "test str"

func _add(x int, y int) int {
    return x + y
}

// multi return
// a, b := swap("hello", "world")
func _swap(x, y string) (string, string) {
    return y, x
}

// struct type
type Vertex struct {
    X int
    Y int
}


func main() {
    sum := 0
    fmt.Println("Happy", math.Pi, "Day")
    fmt.Printf("Now you have %d problems. \n", x)

    return
}


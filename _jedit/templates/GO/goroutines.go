/*
func test_goroutines() {
    // goroutine: is a lightweight thread managed by the Go runtime.
    // go _say("world")
    // _say("hello")

    // uso di channel per scambio dati
    a := []int{7, 2, 8, -9, 4, 0}
    c := make(chan int)
    go _sum(a[:len(a)/2], c)
    go _sum(a[len(a)/2:], c)
    x, y := <-c, <-c // receive from c
    fmt.Println(x, y, x+y)
}
func _say(s string) {
    for i := 0; i < 5; i++ {
        time.Sleep(100 * time.Millisecond)
        fmt.Println(s)
    }
}

func _sum(a []int, c chan int) {
    sum := 0
    for _, v := range a {
        sum += v
    }
    // channel per scambiare dati con il thread principale
    c <- sum // send sum to c
}
*/

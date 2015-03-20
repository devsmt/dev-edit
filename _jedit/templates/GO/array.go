/*
    // A slice points to an array of values and also includes a length.
    // []T is a slice with elements of type T.
    p := []int{2, 3, 5, 7, 11, 13}
    fmt.Println("p ==", p)
    fmt.Println("p[1:4] ==", p[1:4]) // sub slice
    // missing low index implies 0
    fmt.Println("p[:3] ==", p[:3])
    // missing high index implies len(s)
    fmt.Println("p[4:] ==", p[4:])
    x = 3
    fmt.Println("p[x:] ==", p[x:])
    // iteration
    for i := 0; i < len(p); i++ {
        fmt.Printf("p[%d] == %d\n", i, p[i])
    }
    // The zero value of a slice is nil.
    // A nil slice has a length and capacity of 0
    a := make([]int, 5) // len(a)=5
    //To specify a capacity, pass a third argument to make:
    b := make([]int, 0, 5) // len(b)=0, cap(b)=5
    b = b[:cap(b)]         // len(b)=5, cap(b)=5
    a = a[1:]              // len(b)=4, cap(b)=4

    // iteration with range(foreach funziona anche con tipi map)
    var pow = []int{1, 2, 4, 8, 16, 32, 64, 128}
    for i, v := range pow {
        fmt.Printf("2**%d = %d\n", i, v)
    }
    // senza param v
    for i := range pow {
        pow[i] = 1 << uint(i)
    }
    // senza param i
    for _, value := range pow {
        fmt.Printf("%d\n", value)
    }
*/

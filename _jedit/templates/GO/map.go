/*
    // maps
    var m map[string]Vertex

    m = make(map[string]Vertex)
    m["Bell Labs"] = Vertex{
        3, 4,
    }
    fmt.Println("map access:", m["Bell Labs"])

    // map literal
    m = map[string]Vertex{
        "Bell Labs": Vertex{2, 5},
        "Google":    Vertex{5, 8},
    }
    fmt.Println(m)
    // omitted type
    m = map[string]Vertex{
        "Bell Labs": {2, 5},
        "Google":    {5, 8},
    }
    fmt.Println(m)

    // access map values
    mi := make(map[string]int)
    mi["Answer"] = 42
    fmt.Println("The value:", mi["Answer"])
    mi["Answer"] = 48
    fmt.Println("The value:", mi["Answer"])
    delete(m, "Answer")
    fmt.Println("The value:", mi["Answer"])
    v, ok := mi["Answer"]
    fmt.Println("The value:", v, "Present?", ok)

*/

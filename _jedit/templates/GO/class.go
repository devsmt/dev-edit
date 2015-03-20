/*

type Abser interface {
    Abs() float64
}

type _Vertex struct {
    X, Y float64
}

// constructor
func NewVertex(pX float64, pY float64) *_Vertex {
    return &_Vertex{ X:pX, Y:pY }
}

// non sarebbe possibile modificare i valori della struct senza usare un pointer receiver
func (v *_Vertex) Abs() float64 {
    v.X = 1 + v.X
    return math.Sqrt(v.X*v.X + v.Y*v.Y)
}
func (f *fakeFetcher) Fetch(url string) (string, []string, error) {
    res, ok := (*f)[url];
    if ok {
        return res.body, res.urls, nil
    }
    return "", nil, fmt.Errorf("not found: %s", url)
}

func test_methods() {
    // has type *Vertex
    v := &_Vertex{3, 4}
    fmt.Println("OO:", v)
    fmt.Println("method call: ", v.Abs())

    // zeroed T value and returns a pointer to it.
    v1 := new(_Vertex)
    fmt.Println(v)
    v1.X, v1.Y = 11.22, 9.33
    fmt.Println(v1)

    var a, b Abser
    a = v // a *Vertex implements Abser
    //b = &Vertex{1, 2}  // a Vertex, does NOT
    b = &_Vertex{1, 2} // correct form
    fmt.Println(a.Abs() + b.Abs())
}
//-------------------------------------------------------
*/

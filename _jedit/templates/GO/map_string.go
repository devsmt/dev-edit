
type _mymap map[string]*_mymap_prop

type _mymap_prop struct {
    body string
    urls []string
}

// fetcher is a populated _mymap.
var fetcher = &_mymap{
    "http://golang.org/": &_mymap_prop{
        "The Go Programming Language",
        []string{
            "http://golang.org/pkg/",
            "http://golang.org/cmd/",
        },
    }
}

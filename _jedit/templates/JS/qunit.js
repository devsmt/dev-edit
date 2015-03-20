//Separate tests into modules.
module("Module B");

test("some other test", function() {
  //Specify how many assertions are expected to run within a test.
  expect(2);
  //A comparison assertion, equivalent to JUnit's assertEquals.
  equals( true, false, "failing test" );
  equals( true, true, "passing test" );
});

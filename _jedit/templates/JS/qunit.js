//Separate tests into modules.
module("Module B");

test("some other test", function() {
  //Specify how many assertions are expected to run within a test.
  expect(2);
  //A comparison assertion, equivalent to JUnit's assertEquals.
  equal( true, false, "failing test" );
  equal( true, true, "passing test" );
});

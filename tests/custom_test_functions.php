<?php

if (! function_exists('get_test_data')) {
    /**
     * Loads JSON test data and converts it to array.
     *
     * Useful for when we want to check lots of test data and we dont want
     * to put it inline inside the tests.
     *
     * @param string $filePath The file name or path under the test Data directory.
     * @return array The JSON test data converted to array.
     */
    function get_test_data(string $filePath): array
    {
        return json_decode(file_get_contents(base_path('tests/Data/' . $filePath)), true);
    }
}

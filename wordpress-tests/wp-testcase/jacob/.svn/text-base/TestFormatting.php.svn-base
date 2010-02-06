<?php

/*
From http://wordpress.svn.dragonu.net/unittest/wp-unittest/UnitTests/
*/

define('TEST_DATA', DIR_TESTDATA.'/jacob/');







class _WPFormattingTest extends WPTestCase {
    
    function file_test($name, $callback) {
        $input = get_testdata($name . ".input.txt");
        $output = get_testdata($name . ".output.txt");
        for ($i=0; $i<count($input); ++$i) {
            $in = call_user_func($callback, $input[$i]);
            $this->assertEquals($output[$i], $in);
        }
    }
    
}

/*
Get test data from files, one test per line.
Comments start with "###".
*/
function get_testdata($name) {
    $data = file(TEST_DATA . $name);
    $odata = array();
    foreach ($data as $datum) {
        // comment
        $commentpos = strpos($datum, "###");
        if ($commentpos !== false) {
            $datum = trim(substr($datum, 0, $commentpos));
            if (!$datum)
                continue;
        }
        $odata[] = $datum;
    }
    return $odata;
}



/* The `clean_pre` function removes pararaph and line break
   tags within `<pre>` elements as part of the nightmare that
   is `wpautop`. */
class Test_Clean_Pre extends _WPFormattingTest {
    function test_removes_self_closing_br_with_space() {
        $source = 'a b c\n<br />sldfj<br />';
        $res = 'a b c\nsldfj';
        
        $this->assertEquals($res, clean_pre($source));
    }
    function test_removes_self_closing_br_without_space() {
        $source = 'a b c\n<br/>sldfj<br/>';
        $res = 'a b c\nsldfj';
        $this->assertEquals($res, clean_pre($source));
    }
    // I don't think this can ever happen in production;
    // <br> is changed to <br /> elsewhere. Left in because
    // that replacement shouldn't happen (what if you want
    // HTML 4 output?).
    function test_removes_html_br() {
        $source = 'a b c\n<br>sldfj<br>';
        $res = 'a b c\nsldfj';
        $this->assertEquals($res, clean_pre($source));
    }
    function test_removes_p() {
        $source = "<p>isn't this exciting!</p><p>oh indeed!</p>";
        $res = "\nisn't this exciting!\noh indeed!";
        $this->assertEquals($res, clean_pre($source));
    }
}

/*
`seems_utf8` returns true for utf-8 strings, false otherwise.
*/
class Test_Seems_UTF8 extends _WPFormattingTest {
    function test_returns_true_for_utf8_strings() {
        // from http://www.i18nguy.com/unicode-example.html
        $utf8 = get_testdata('utf-8.txt');
        $this->assertTrue(count($utf8) > 3);
        foreach ($utf8 as $string) {
            $this->assertTrue(seems_utf8($string));
        }
    }
    function test_returns_false_for_non_utf8_strings() {
        $big5 = get_testdata('test_big5.txt');
        $big5 = $big5[0];
        $strings = array(
            "abc",
            "123",
            $big5
        );
    }
}

/*
Escapes HTML special characters (&, <, >); does not encode
ampersands if they are already part of entities.
*/
class Test_WP_Specialchars extends _WPFormattingTest {
    function test_escapes_ampersands() {
        $source = "penn & teller & at&t";
        $res = "penn &amp; teller &amp; at&amp;t";
        $this->assertEquals($res, wp_specialchars($source));
    }
    function test_escapes_greater_and_less_than() {
        $source = "this > that < that <randomhtml />";
        $res = "this &gt; that &lt; that &lt;randomhtml /&gt;";
        $this->assertEquals($res, wp_specialchars($source));
    }
    function test_optionally_escapes_quotes() {
        $source = "\"'hello!'\"";
        $this->assertEquals('"&#039;hello!&#039;"', wp_specialchars($source, 'single'));
        $this->assertEquals("&quot;'hello!'&quot;", wp_specialchars($source, 'double'));
        $this->assertEquals('&quot;&#039;hello!&#039;&quot;', wp_specialchars($source, true));
        $this->assertEquals($source, wp_specialchars($source));
    }
    function test_ignores_existing_entities() {
        $source = '&#038; &#x00A3; &#022; &amp;';
        $res = '&amp; &#x00A3; &#022; &amp;';
        $this->assertEquals($res, wp_specialchars($source));
    }
}

class Test_UTF8_URI_Encode extends _WPFormattingTest {
    /*
    Non-ASCII UTF-8 characters should be percent encoded. Spaces etc.
    are dealt with elsewhere.
    */
    function test_percent_encodes_non_reserved_characters() {
        $utf8urls = get_testdata('utf-8.txt');
        $urlencoded = get_testdata('utf-8-urlencoded.txt');
        for ($i=0; $i<count($utf8urls); ++$i) {
            $this->assertEquals($urlencoded[$i], utf8_uri_encode($utf8urls[$i]));
        }
    }
    function test_output_is_not_longer_than_optional_length_argument() {
        $utf8urls = get_testdata('utf-8.txt');
        foreach ($utf8urls as $url) {
            $maxlen = rand(5, 200);
            $this->assertTrue(strlen(utf8_uri_encode($url, $maxlen)) <= $maxlen);
        }
        
    }
    
}

/*
Removes accents from characters and decomposes ligatures.
*/
class Test_Remove_Accents extends _WPFormattingTest {
    
    /*
    http://www.alanwood.net/unicode/latin_1_supplement.html

    unicode-latin-1-supplement is identical to iso-8859-1, so these
    tests on their own will never take the unicode codepath
    */    
    function test_removes_accents_from_decomposable_latin1_supplement() {
        $this->file_test("removes_accents_from_decomposable_latin1_supplement",
            "remove_accents");
    }
    
    /*
    Several characters, such as eth and thorn, do not have a unicode
    decomposition, but should be replaced. The eth, for example, should become
    "d" or "dh", and the thorn "th". They require special rules.
    */
    function test_removes_accents_from_undecomposable_latin1_supplement() {
        $this->file_test("removes_accents_from_undecomposable_latin1_supplement",
            "remove_accents");
    }   
    function test_removes_accents_from_latin1_supplement() {
        $this->file_test("removes_accents_from_latin1_supplement",
            "remove_accents");
    }

    function test_removes_accents_from_decomposable_latin_extended_a() {
        $this->file_test("removes_accents_from_decomposable_latin_extended_a",
            "remove_accents");
    }    
    function test_removes_accents_from_undecomposable_latin_extended_a() {
        $this->file_test("removes_accents_from_undecomposable_latin_extended_a",
            "remove_accents");
    }   
    function test_removes_accents_from_latin_extended_a() {
        $this->file_test("removes_accents_from_latin_extended_a",
            "remove_accents");
    }

    // Currently this test fails because the unicode codepath (seems_utf8 == true)
    // can't handle non-decomposable characters (eth and friends).
    function test_removes_accents_from_latin1_supplement_and_latin_extended_a() {
        $this->file_test("removes_accents_from_latin1_supplement_and_latin_extended_a",
            "remove_accents");
    }
}

/*
Sanitizes filenames.
*/
class Test_Sanitize_File_Name extends _WPFormattingTest {
    function test_makes_lowercase() {
        $this->assertEquals("att", sanitize_file_name("ATT"));
    }
    function test_removes_entities() {
        $this->assertEquals("att", sanitize_file_name("at&amp;t"));
    }
    function test_replaces_underscores_with_hyphens() {
        $this->assertEquals("a-t-t", sanitize_file_name("a_t_t"));
    }
    function test_replaces_any_amount_of_whitespace_with_one_hyphen() {
        $this->assertEquals("a-t", sanitize_file_name("a          t"));
        $this->assertEquals("a-t", sanitize_file_name("a    \n\n\nt"));
    }
    function test_replaces_any_number_of_hyphens_with_one_hyphen() {
        $this->assertEquals("a-t-t", sanitize_file_name("a----t----t"));
    }
    function test_trims_trailing_hyphens() {
        $this->assertEquals("a-t-t", sanitize_file_name("a----t----t----"));
    }
    function test_strips_anything_but_alphanums_periods_and_hyphens() {
        $this->assertEquals("saint-sans", sanitize_file_name("S%ain%t-S%aëns"));
    }
    function test_handles_non_entity_ampersands() {
        $this->assertEquals("penn-teller-bull", sanitize_file_name("penn & teller; bull"));
    }
}

/*
Mathilda: Do you "clean" anyone?
Léon: No women, no kids, that's the rules.
*/
class Test_Sanitize_User extends _WPFormattingTest {
    function test_strips_html() {
        $input = "Captain <strong>Awesome</strong>";
        $expected = "Captain Awesome";
        $this->assertEquals($expected, sanitize_user($input));
    }
    function test_strips_entities() {
        $this->assertEquals("ATT", sanitize_user("AT&amp;T"));
    }
    function test_strips_percent_encoded_octets() {
        $this->assertEquals("Franois", sanitize_user("Fran%c3%a7ois"));
    }
    function test_optional_strict_mode_reduces_to_safe_ascii_subset() {
        $this->assertEquals("abc", sanitize_user("()~ab~öcö!", true));
    }
}

class Test_Sanitize_Title extends _WPFormattingTest {
    function test_strips_html() {
        $input = "Captain <strong>Awesome</strong>";
        $expected = "Captain Awesome";
        $this->assertEquals($expected, sanitize_title($input));
    }
    
    function test_titles_sanitized_to_nothing_are_replaced_with_optional_fallback() {
        $input = "<strong></strong>";
        $fallback = "Captain Awesome";
        $this->assertEquals($fallback, sanitize_title($input, $fallback));
    }
}

class Test_Sanitize_Title_With_Dashes extends _WPFormattingTest {
    function test_strips_html() {
        $input = "Captain <strong>Awesome</strong>";
        $expected = "Captain Awesome";
        $this->assertEquals($expected, sanitize_title($input));
    }
    function test_strips_unencoded_percent_signs() {
        $this->assertEquals("fran%c3%a7ois", sanitize_title_with_dashes("fran%c3%a7%ois"));
    }
    function test_makes_title_lowercase() {
        $this->assertEquals("abc", sanitize_title_with_dashes("ABC"));
    }
    function test_replaces_any_amount_of_whitespace_with_one_hyphen() {
        $this->assertEquals("a-t", sanitize_title_with_dashes("a          t"));
        $this->assertEquals("a-t", sanitize_title_with_dashes("a    \n\n\nt"));
    }
    function test_replaces_any_number_of_hyphens_with_one_hyphen() {
        $this->assertEquals("a-t-t", sanitize_title_with_dashes("a----t----t"));
    }
    function test_trims_trailing_hyphens() {
        $this->assertEquals("a-t-t", sanitize_title_with_dashes("a----t----t----"));
    }
    function test_handles_non_entity_ampersands() {
        $this->assertEquals("penn-teller-bull", sanitize_title_with_dashes("penn & teller; bull"));
    }
}

/*
`convert_chars` is a poorly named function that does
four unrelated tasks. ;)
*/
class Test_Convert_Chars extends _WPFormattingTest {
    function test_replaces_windows1252_entities_with_unicode_ones() {
        $input = "&#130;&#131;&#132;&#133;&#134;&#135;&#136;&#137;&#138;&#139;&#140;&#145;&#146;&#147;&#148;&#149;&#150;&#151;&#152;&#153;&#154;&#155;&#156;&#159;";
        $output = "&#8218;&#402;&#8222;&#8230;&#8224;&#8225;&#710;&#8240;&#352;&#8249;&#338;&#8216;&#8217;&#8220;&#8221;&#8226;&#8211;&#8212;&#732;&#8482;&#353;&#8250;&#339;&#376;";
        $this->assertEquals($output, convert_chars($input));
    }
    function test_converts_html_br_and_hr_to_the_xhtml_self_closing_variety() {
        $inputs = array(
            "abc <br> lol <br />" => "abc <br /> lol <br />",
            "<BR> HO HO <HR>"     => "<br /> ho ho <hr />",
            "<hr><br>"            => "<hr /><br />"
            );
        foreach ($inputs as $input => $expected) {
            $this->assertEquals($expected, convert_chars($input));
        }
    }
    function test_escapes_lone_ampersands() {
        $this->assertEquals("at&#038;t", convert_chars("at&t"));
    }
    // what the hell are these? O_o
    function test_removes_category_and_title_metadata_tags() {
        $this->assertEquals("", convert_chars("<title><div class='lol'>abc</div></title><category>a</category>"));
    }
}

class Test_Funky_JavaScript_Fix extends _WPFormattingTest {
    function test_does_nothing_if_not_mac_or_win_ie() {
        global $is_macIE, $is_winIE;
        $prev = array($is_macIE, $is_winIE);
        $is_macIE = $is_winIE = false;
        $data = get_testdata("utf-8-u-urlencoded.txt");
        foreach ($data as $datum) {
            $this->assertEquals($datum, funky_javascript_fix($datum));
        }
        $is_macIE = $prev[0];
        $is_winIE = $prev[1];
    }
    function test_converts_u_percent_encoded_values_if_mac_ie() {
        global $is_macIE;
        $prev = $is_macIE;
        $is_macIE = true;
        $this->_run();
        $is_macIE = $prev;
    }
    function test_converts_u_percent_encoded_values_if_win_ie() {
        global $is_winIE;
        $prev = $is_winIE;
        $is_winIE = true;
        $this->_run();
        $is_winIE = $prev;
    }
    function _run() {
        $input = get_testdata("utf-8-u-urlencoded.txt");
        $output = get_testdata("utf-8-entitized.txt");
        for ($i=0; $i<count($input); ++$i) {
            $this->assertEquals($output[$i], funky_javascript_fix($input[$i]));
        }
    }
}


class Test_BalanceTags extends _WPFormattingTest {
    function test_adds_missing_end_tags() {
        $this->assertEquals("<b><i>abc</i></b>", balanceTags("<b><i>abc</b>", true));
    }
    function test_fixes_simple_bad_nesting() {
        $this->assertEquals("<b><i>abc</i></b>", balanceTags("<b><i>abc</b></i>", true));
    }
}

class Test_Zeroise extends _WPFormattingTest {
    function test_pads_with_leading_zeroes() {
        $this->assertEquals("00005", zeroise(5, 5));
    }
    function test_does_nothing_if_input_is_already_longer() {
        $this->assertEquals("5000000", zeroise(5000000, 2));
    }
}

class Test_Backslashit extends _WPFormattingTest {
    function test_backslashes_alphas() {
        $this->assertEquals("\\a943\\b\\c", backslashit("a943bc"));
    }
    function test_double_backslashes_leading_numbers() {
        $this->assertEquals("\\\\95", backslashit("95"));
    }
}

class Test_Untrailingslashit extends _WPFormattingTest {
    function test_removes_trailing_slashes() {
        $this->assertEquals("a", untrailingslashit("a/"));
        $this->assertEquals("a", untrailingslashit("a////"));
    }
}

class Test_Trailingslashit extends _WPFormattingTest {
    function test_adds_trailing_slash() {
        $this->assertEquals("a/", trailingslashit("a"));
    }
    function test_does_not_add_trailing_slash_if_one_exists() {
        $this->assertEquals("a/", trailingslashit("a/"));
    }
}

class Test_Is_Email extends _WPFormattingTest {
    function test_returns_true_if_given_a_valid_email_address() {
        $data = array(
            "bob@example.com",
            '"Bob Johnson" <bob@example.com>',
            "phil@example.info",
            "ace@204.32.222.14",
            "kevin@many.subdomains.make.a.happy.man.edu"
            );
        foreach ($data as $datum) {
            $this->assertTrue(is_email($datum), $datum);
        }
    }
    // TODO: make up some more useful test data :)
    function test_returns_false_if_given_an_invalid_email_address() {
        $data = array(
            "khaaaaaaaaaaaaaaan!",
            'http://bob.example.com/',
            "sif i'd give u it, spamer!1",
            "com.exampleNOSPAMbob",
            "bob@your mom"
            );
        foreach ($data as $datum) {
            $this->assertFalse(is_email($datum), $datum);
        }
    }
}

/*
Decodes text in RFC2047 "Q"-encoding, e.g.

    =?iso-8859-1?q?this=20is=20some=20text?=
*/
class Test_WP_ISO_Descrambler extends _WPFormattingTest {
    function test_decodes_iso_8859_1_rfc2047_q_encoding() {
        $this->assertEquals("this is some text", wp_iso_descrambler("=?iso-8859-1?q?this=20is=20some=20text?="));
    }
}

class Test_Ent2NCR extends _WPFormattingTest {
    function test_converts_named_entities_to_numeric_character_references() {
        $data = get_testdata("entities.txt");
        foreach ($data as $datum) {
            $parts = explode("|", $datum);
            $name = "&" . trim($parts[0]) . ";";
            $ncr = trim($parts[1]);
            $this->assertEquals("&#".$ncr.";", ent2ncr($name), $name);
        }
    }
}

?>

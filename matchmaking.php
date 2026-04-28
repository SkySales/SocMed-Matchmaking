<?php
include('config.php');

/* 🔹 CLEAN TEXT */
function clean_text($text){
    $text = strtolower($text);
    $text = preg_replace("/[^a-z0-9 ]/", " ", $text);
    return array_filter(explode(" ", $text));
}

/* 🔹 GET USER DOCUMENT (POSTS + TAGS) */
function get_user_words($user_id){
    global $conn;

    $words = [];

    // ✅ GET POSTS
    $sql = "SELECT Caption, HashTags FROM posts WHERE User_ID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    while($row = $result->fetch_assoc()){
        $words = array_merge($words, clean_text($row['Caption']." ".$row['HashTags']));
    }

    $tag_sql = "
        SELECT t.Tag_Name 
        FROM user_tags ut
        JOIN tags t ON ut.Tag_ID = t.Tag_ID
        WHERE ut.User_ID = ?
    ";
    $stmt2 = $conn->prepare($tag_sql);
    $stmt2->bind_param("i", $user_id);
    $stmt2->execute();
    $tags = $stmt2->get_result();

    while($tag = $tags->fetch_assoc()){
        $words[] = strtolower($tag['Tag_Name']);
    }

    return $words;
}

function compute_tf($words){
    $tf = [];
    $total = count($words);

    if($total == 0) return [];

    foreach($words as $word){
        if(!isset($tf[$word])) $tf[$word] = 0;
        $tf[$word]++;
    }

    foreach($tf as $word => $count){
        $tf[$word] = $count / $total;
    }

    return $tf;
}

function compute_idf($documents){
    $idf = [];
    $total_docs = count($documents);

    foreach($documents as $doc){
        foreach(array_unique($doc) as $word){
            if(!isset($idf[$word])) $idf[$word] = 0;
            $idf[$word]++;
        }
    }

    foreach($idf as $word => $count){
        $idf[$word] = log($total_docs / ($count + 1));
    }

    return $idf;
}

function compute_tfidf($tf, $idf){
    $tfidf = [];

    foreach($tf as $word => $val){
        $tfidf[$word] = $val * ($idf[$word] ?? 0);
    }

    return $tfidf;
}

function cosine_similarity($vec1, $vec2){
    $dot = 0;
    $norm1 = 0;
    $norm2 = 0;

    $all_words = array_unique(array_merge(array_keys($vec1), array_keys($vec2)));

    foreach($all_words as $word){
        $v1 = $vec1[$word] ?? 0;
        $v2 = $vec2[$word] ?? 0;

        $dot += $v1 * $v2;
        $norm1 += $v1 * $v1;
        $norm2 += $v2 * $v2;
    }

    if($norm1 == 0 || $norm2 == 0) return 0;

    return $dot / (sqrt($norm1) * sqrt($norm2));
}

function get_match_suggestions($user_id){
    global $conn;

    $sql = "SELECT User_ID, FULL_NAME, USER_NAME, IMAGE FROM users";
    $users = $conn->query($sql);

    $documents = [];
    $user_map = [];

    while($user = $users->fetch_assoc()){
        $words = get_user_words($user['User_ID']);

        $documents[] = $words;
        $user_map[$user['User_ID']] = [
            'data' => $user,
            'words' => $words
        ];
    }

    $idf = compute_idf($documents);

    $vectors = [];

    foreach($user_map as $uid => $info){
        $tf = compute_tf($info['words']);
        $vectors[$uid] = compute_tfidf($tf, $idf);
    }

    $my_vector = $vectors[$user_id] ?? [];

    $suggestions = [];

    foreach($vectors as $uid => $vec){
        if($uid == $user_id) continue;

        $score = cosine_similarity($my_vector, $vec);

        if($score > 0){
            $user_data = $user_map[$uid]['data'];
            $user_data['score'] = round($score * 100, 2); // percentage
            $suggestions[] = $user_data;
        }
    }

    usort($suggestions, function($a, $b){
        return $b['score'] <=> $a['score'];
    });

    usort($suggestions, function($a, $b){
        return $b['score'] <=> $a['score'];
    });
    $top = array_slice($suggestions, 0, 10);

    shuffle($top);
    return array_slice($top, 0, 3);
    }

?>
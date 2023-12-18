<?php


/* ---- ---- ---- ----
 * SUMMARY
 * 
 * resource
 * archive
 * storage
 * 
 * ---- ---- ---- ---- */



/**
 * Get the URL or path for a resource.
 *
 * @param string $source The name or path of the source.
 * @param bool $asPath Whether to return the source's real path (default is false).
 *
 * @return string The URL or path to the specified source.
 */
if (!function_exists('resource')) {
  function resource($source='', $asPath = false)
  {
    if ($asPath) {
      // Return the real path of the source
      return realpath(PATH_RESOURCE . $source);
    } else {
      // Return the URL for the source
      return base_url('resource/' . $source);
    }
  }
}

/**
 * Archive Function
 *
 * This function is used to generate the archive path or URL for a given source file.
 *
 * @param string $source The name of the source file.
 * @param bool $asPath Set to true to get the real file path, false to get the URL.
 *
 * @return string The archive path or URL for the given source file.
 */
if (!function_exists('archive')) {
  function archive($source='', $asPath = false)
  {
    if ($asPath) {
      // Return the real path of the source
      return realpath(PATH_ARCHIVE . $source);
    } else {
      // Return the URL for the source
      return base_url('archive/' . $source);
    }
  }
}

/**
 * Storage Function
 *
 * This function is used to generate the storage path or URL for a given source file.
 *
 * @param string $source The name of the source file.
 * @param bool $asPath Set to true to get the real file path, false to get the URL.
 *
 * @return string The storage path or URL for the given source file.
 */
if (!function_exists('storage')) {
  function storage($source='', $asPath = false)
  {
    if ($asPath) {
      // Return the real path of the source
      return realpath(PATH_STORAGE . $source);
    } else {
      // Return the URL for the source
      return base_url('storage/' . $source);
    }
  }
}


/**
 * Storage Function
 *
 * This function is used to generate the storage path or URL for a given source file.
 *
 * @param string $source The name of the source file.
 * @param bool $asPath Set to true to get the real file path, false to get the URL.
 *
 * @return string The storage path or URL for the given source file.
 */
if (!function_exists('node_modules')) {
  function node_modules($source='', $asPath = false)
  {
    if ($asPath) {
      // Return the real path of the source
      return realpath(PATH_ROOT . '/node_modules/' . $source);
    } else {
      // Return the URL for the source
      return base_url('/node_modules/' . $source);
    }
  }
}

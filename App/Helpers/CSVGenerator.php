<?php

namespace Helpers;

class CSVGenerator
{
	private $cells = [];
	private $entries = 0;

	public function addColumns( $column_names )
	{
		if ( $this->entries > 0 && !empty( $this->cells ) ) {
			throw new \Exception( "Cannot add new columns once entries are added to the file." );
		}

		if ( is_array( $column_names ) ) {
			foreach ( $column_names as $name ) {
				$this->cells[ 0 ][] = $name;
			}

			return $this;
		}

		$this->cells[ 0 ][] = $column_name;
		return $this;
	}

	public function addEntry( array $entry )
	{
		// Increment the entry indicator
		$this->entries++;

		// Throw error if the incorrect number of columns
		if ( count( $entry ) != count( $this->cells[ 0 ] ) ) {
			throw new \Exception( "Entry #{$this->entries} does not have the correct number of columns. Columns: " . implode( ", ", $this->cells[ 0 ] ) );
		}

		// Add the new entry to the cells
		// The index for the new entry will be the sum of the current cells
		$this->cells[ count( $this->cells ) ] = $entry;

		return $this;
	}

	public function download( $filename = "data" )
	{
		header( "Content-Type: text/csv" );
        header( "Content-Disposition: attachment; filename={$filename}.csv" );

        $fp = fopen( "php://output", "wb" );

        foreach ( $this->cells as $line ) {
            fputcsv( $fp, $line, "," );
        }

        fclose( $fp );
	}

	public function resetEntryCounter()
	{
		$this->entries = 0;
	}
}

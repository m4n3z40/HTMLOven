<?php namespace HTMLOven;

interface HTMLReferenceInterface
{
	function setTags(array $tags);
	function getTags();
	function addTag(array $tag);
	function getTag($tagName);
	function removeTag();
	function clearTags();
	function needsClosingTag($tagName);
	function innerTextAllowed($tagName);
	function optionalValuesAllowed();
}
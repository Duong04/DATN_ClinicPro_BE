<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# source: google/protobuf/descriptor.proto

namespace Google\Protobuf\Internal\GeneratedCodeInfo;

use Google\Protobuf\Internal\GPBType;
use Google\Protobuf\Internal\GPBWire;
use Google\Protobuf\Internal\RepeatedField;
use Google\Protobuf\Internal\InputStream;
use Google\Protobuf\Internal\GPBUtil;

/**
 * Generated from protobuf message <code>google.protobuf.GeneratedCodeInfo.Annotation</code>
 */
class Annotation extends \Google\Protobuf\Internal\Message
{
    /**
     * Identifies the element in the original source .proto file. This field
     * is formatted the same as SourceCodeInfo.Location.path.
     *
     * Generated from protobuf field <code>repeated int32 path = 1 [packed = true];</code>
     */
    private $path;
    /**
     * Identifies the filesystem path to the original source .proto.
     *
     * Generated from protobuf field <code>optional string source_file = 2;</code>
     */
    protected $source_file = null;
    /**
     * Identifies the starting offset in bytes in the generated code
     * that relates to the identified object.
     *
     * Generated from protobuf field <code>optional int32 begin = 3;</code>
     */
    protected $begin = null;
    /**
     * Identifies the ending offset in bytes in the generated code that
     * relates to the identified object. The end offset should be one past
     * the last relevant byte (so the length of the text = end - begin).
     *
     * Generated from protobuf field <code>optional int32 end = 4;</code>
     */
    protected $end = null;
    /**
     * Generated from protobuf field <code>optional .google.protobuf.GeneratedCodeInfo.Annotation.Semantic semantic = 5;</code>
     */
    protected $semantic = null;

    /**
     * Constructor.
     *
     * @param array $data {
     *     Optional. Data for populating the Message object.
     *
     *     @type array<int>|\Google\Protobuf\Internal\RepeatedField $path
     *           Identifies the element in the original source .proto file. This field
     *           is formatted the same as SourceCodeInfo.Location.path.
     *     @type string $source_file
     *           Identifies the filesystem path to the original source .proto.
     *     @type int $begin
     *           Identifies the starting offset in bytes in the generated code
     *           that relates to the identified object.
     *     @type int $end
     *           Identifies the ending offset in bytes in the generated code that
     *           relates to the identified object. The end offset should be one past
     *           the last relevant byte (so the length of the text = end - begin).
     *     @type int $semantic
     * }
     */
    public function __construct($data = NULL) {
        \GPBMetadata\Google\Protobuf\Internal\Descriptor::initOnce();
        parent::__construct($data);
    }

    /**
     * Identifies the element in the original source .proto file. This field
     * is formatted the same as SourceCodeInfo.Location.path.
     *
     * Generated from protobuf field <code>repeated int32 path = 1 [packed = true];</code>
     * @return \Google\Protobuf\Internal\RepeatedField
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Identifies the element in the original source .proto file. This field
     * is formatted the same as SourceCodeInfo.Location.path.
     *
     * Generated from protobuf field <code>repeated int32 path = 1 [packed = true];</code>
     * @param array<int>|\Google\Protobuf\Internal\RepeatedField $var
     * @return $this
     */
    public function setPath($var)
    {
        $arr = GPBUtil::checkRepeatedField($var, \Google\Protobuf\Internal\GPBType::INT32);
        $this->path = $arr;

        return $this;
    }

    /**
     * Identifies the filesystem path to the original source .proto.
     *
     * Generated from protobuf field <code>optional string source_file = 2;</code>
     * @return string
     */
    public function getSourceFile()
    {
        return isset($this->source_file) ? $this->source_file : '';
    }

    public function hasSourceFile()
    {
        return isset($this->source_file);
    }

    public function clearSourceFile()
    {
        unset($this->source_file);
    }

    /**
     * Identifies the filesystem path to the original source .proto.
     *
     * Generated from protobuf field <code>optional string source_file = 2;</code>
     * @param string $var
     * @return $this
     */
    public function setSourceFile($var)
    {
        GPBUtil::checkString($var, True);
        $this->source_file = $var;

        return $this;
    }

    /**
     * Identifies the starting offset in bytes in the generated code
     * that relates to the identified object.
     *
     * Generated from protobuf field <code>optional int32 begin = 3;</code>
     * @return int
     */
    public function getBegin()
    {
        return isset($this->begin) ? $this->begin : 0;
    }

    public function hasBegin()
    {
        return isset($this->begin);
    }

    public function clearBegin()
    {
        unset($this->begin);
    }

    /**
     * Identifies the starting offset in bytes in the generated code
     * that relates to the identified object.
     *
     * Generated from protobuf field <code>optional int32 begin = 3;</code>
     * @param int $var
     * @return $this
     */
    public function setBegin($var)
    {
        GPBUtil::checkInt32($var);
        $this->begin = $var;

        return $this;
    }

    /**
     * Identifies the ending offset in bytes in the generated code that
     * relates to the identified object. The end offset should be one past
     * the last relevant byte (so the length of the text = end - begin).
     *
     * Generated from protobuf field <code>optional int32 end = 4;</code>
     * @return int
     */
    public function getEnd()
    {
        return isset($this->end) ? $this->end : 0;
    }

    public function hasEnd()
    {
        return isset($this->end);
    }

    public function clearEnd()
    {
        unset($this->end);
    }

    /**
     * Identifies the ending offset in bytes in the generated code that
     * relates to the identified object. The end offset should be one past
     * the last relevant byte (so the length of the text = end - begin).
     *
     * Generated from protobuf field <code>optional int32 end = 4;</code>
     * @param int $var
     * @return $this
     */
    public function setEnd($var)
    {
        GPBUtil::checkInt32($var);
        $this->end = $var;

        return $this;
    }

    /**
     * Generated from protobuf field <code>optional .google.protobuf.GeneratedCodeInfo.Annotation.Semantic semantic = 5;</code>
     * @return int
     */
    public function getSemantic()
    {
        return isset($this->semantic) ? $this->semantic : 0;
    }

    public function hasSemantic()
    {
        return isset($this->semantic);
    }

    public function clearSemantic()
    {
        unset($this->semantic);
    }

    /**
     * Generated from protobuf field <code>optional .google.protobuf.GeneratedCodeInfo.Annotation.Semantic semantic = 5;</code>
     * @param int $var
     * @return $this
     */
    public function setSemantic($var)
    {
        GPBUtil::checkEnum($var, \Google\Protobuf\Internal\GeneratedCodeInfo\Annotation\Semantic::class);
        $this->semantic = $var;

        return $this;
    }

}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(Annotation::class, \Google\Protobuf\Internal\GeneratedCodeInfo_Annotation::class);

